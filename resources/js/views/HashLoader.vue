<template>
  <div id="seed-generate">
    <div
      v-if="rom && rom.build === '2019-10-31'"
      class="alert alert-danger"
      style="font-family:'Cabin',sans-serif"
      role="alert"
    >
      <strong>BE ADVISED:</strong> this festive has effects, which may potentially trigger seizures for people with photosensitive epilepsy.
    </div>
    <div v-if="error" class="alert alert-danger" role="alert">
      <button type="button" class="close" aria-label="Close">
        <img class="icon" src="/i/svg/x.svg" alt="clear" @click="error = false" />
      </button>
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
      <span class="sr-only">{{ $t('error.title') }}:</span>
      <span class="message">{{ this.error }}</span>
    </div>
    <rom-loader
      v-if="!romLoaded"
      @update="updateRom"
      @error="onError"
      :current-rom-hash="current_rom_hash"
      :override-base-bps="overrideBaseBps"
    ></rom-loader>

    <div id="seed-details" class="card border-success" v-if="gameLoaded && romLoaded">
      <div class="card-header bg-success card-heading-btn" :class="{'bg-info': rom.tournament}">
        <h3
          class="card-title text-white float-left"
        >{{ rom.name || $t('randomizer.details.title') }}</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md mb-3">
            <vt-rom-info :rom="rom"></vt-rom-info>
          </div>
          <div class="col-md mb-3">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group">
                  <button
                    class="btn btn-light border-secondary text-center"
                    @click="saveSpoiler"
                  >{{ $t('randomizer.details.save_spoiler') }}</button>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group">
                  <button
                    class="btn btn-success text-center"
                    :disabled="disableSaveRomButton"
                    @click="saveRom"
                  >{{ $t('randomizer.details.save_rom') }}</button>
                </div>
              </div>
            </div>
            <div class="row">
              <vt-rom-settings
                class="col-12"
                :rom="rom"
                @disallow-save-rom="disallowSaveRom"
              ></vt-rom-settings>
            </div>
          </div>
        </div>
        <vt-spoiler :rom="rom"></vt-spoiler>
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from "../core/event-bus";
import FileSaver from "file-saver";
import localforage from "localforage";
import axios from "axios";
import { mapState } from "vuex";
import RomLoader from "../components/VTRomLoader.vue";

export default {
  components: {
    RomLoader
  },
  props: {
    current_rom_hash: {
      type: String,
      required: true
    },
    overrideBaseBps: {
      type: String,
      required: true
    },
    hash: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      rom: null,
      error: false,
      generating: false,
      romLoaded: false,
      disableSaveRomButton: false,
      gameLoaded: false
    };
  },
  created() {
    this.$store.dispatch("getSprites");
    this.$store.dispatch("romSettings/initialize");

    localforage.getItem("rom").then(function(blob) {
      if (blob == null) {
        EventBus.$emit("noBlob");
        return;
      }
      EventBus.$emit("loadBlob", { target: { files: [new Blob([blob])] } });
    });
  },
  mounted() {
    EventBus.$on("applyHash", this.applyHash);
  },
  methods: {
    disallowSaveRom(e) {
      this.disableSaveRomButton = Boolean(e);
    },
    applyHash(e, second_attempt) {
      if (this.rom.checkMD5() != this.current_rom_hash) {
        if (second_attempt) {
          return new Promise.reject(this.rom);
        }
        return this.rom
          .reset()
          .then(
            function() {
              return this.applyHash(e, true);
            }.bind(this)
          )
          .catch(error => {
            console.log(error);
          });
      }
      this.gameLoaded = false;
      return this.applyPatch();
    },
    applyPatchFromServer() {
      return new Promise(resolve => {
        axios.post(`/hash/` + this.hash).then(response => {
          this.rom.parsePatch(response.data).then(() => {
            if (this.rom.shuffle || this.rom.spoilers == "mystery" || this.rom.allow_quickswap) {
              this.rom.allowQuickSwap = true;
            }
            this.gameLoaded = true;
            EventBus.$emit("gameLoaded", this.rom);
            resolve({ rom: this.rom, patch: response.data.patch });
          });
        });
      });
    },
    applyPatch() {
      if (!window.s3_prefix) {
        return this.applyPatchFromServer();
      }
      return new Promise(resolve => {
        axios
          .get(window.s3_prefix + "/" + this.hash + ".json", {
            transformRequest: [
              (data, headers) => {
                delete headers.common;
                return data;
              }
            ]
          })
          .then(response => {
            this.rom.parsePatch(response.data).then(() => {
              console.log("loaded from s3 :)");
              if (this.rom.shuffle || this.rom.spoilers == "mystery" || this.rom.allow_quickswap) {
                this.rom.allowQuickSwap = true;
              }
              this.gameLoaded = true;
              EventBus.$emit("gameLoaded", this.rom);
              resolve({ rom: this.rom, patch: response.data.patch });
            });
          })
          .catch(this.applyPatchFromServer);
      });
    },
    saveRom() {
      return this.rom.save(this.rom.downloadFilename() + ".sfc", {
        quickswap: this.quickswap,
        paletteShuffle: this.paletteShuffle,
        musicOn: this.musicOn,
        reduceFlashing: this.reduceFlashing
      });
    },
    saveSpoiler() {
      return FileSaver.saveAs(
        new Blob([JSON.stringify(this.rom.spoiler, null, 4)]),
        this.rom.downloadFilename() + ".txt"
      );
    },
    updateRom(rom) {
      if (!rom) {
        console.log(rom);
        return;
      }
      this.rom = rom;
      this.error = false;
      this.romLoaded = true;
    },
    onError(error) {
      this.error = error;
    }
  },
  computed: {
    ...mapState("romSettings", {
      heartSpeed: state => state.heartSpeed,
      menuSpeed: state => state.menuSpeed,
      heartColor: state => state.heartColor,
      quickswap: state => state.quickswap,
      musicOn: state => state.musicOn,
      paletteShuffle: state => state.paletteShuffle,
      reduceFlashing: state => state.reduceFlashing
    })
  }
};
</script>
