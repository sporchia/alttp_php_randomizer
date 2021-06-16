<template>
  <div id="seed-generate">
    <div v-if="error" class="alert alert-danger" role="alert">
      <button type="button" class="close" aria-label="Close">
        <img class="icon" src="/i/svg/x.svg" alt="clear" @click="error = false" />
      </button>
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
      <span class="sr-only">{{ $t('error.title') }}:</span>
      <span class="message">{{ this.error }}</span>
    </div>
    <rom-loader v-if="!romLoaded" @update="updateRom" @error="onError"></rom-loader>
    <div v-if="romLoaded && !gameLoaded && !generating" class="card border-success">
      <div class="card-header bg-success card-heading-btn">
        <h3 class="card-title text-white float-left">{{ $t('randomizer.title') }}</h3>
        <div class="btn-toolbar float-right" v-if="gameGenerated">
          <a
            class="btn btn-light text-dark border-secondary"
            role="button"
            @click="gameLoaded = true"
          >
            {{ $t('randomizer.generate.forward') }}
            <img class="icon" src="/i/svg/arrow-right.svg" alt />
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="card border-info my-1">
          <div class="card-body">
            <div class="row">
              <div class="col my-1">
                <Select :value="preset" @input="setPreset" :options="optionsPreset">
                  <template v-slot:default>{{ $t('randomizer.preset.title') }}</template>
                  <template v-slot:appends v-if="preset.value !== 'custom'">
                    <button
                      class="btn btn-outline-secondary"
                      type="button"
                      @click="setPreset('custom')"
                    >{{ $t('randomizer.preset.customize') }}</button>
                  </template>
                </Select>
              </div>
            </div>
          </div>
          <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.placement.title') }}</h5>
          <div class="card-body">
            <div class="row" v-if="!editable">
              <div
                class="col-xl-3 col-lg-6 my-1"
              >{{ $t('randomizer.glitches_required.title') }}: {{ $t(glitchesRequired.name) }}</div>
              <div
                class="col-xl-3 col-lg-6 my-1"
              >{{ $t('randomizer.item_placement.title') }}: {{ $t(itemPlacement.name) }}</div>
              <div
                class="col-xl-3 col-lg-6 my-1"
              >{{ $t('randomizer.dungeon_items.title') }}: {{ $t(dungeonItems.name) }}</div>
              <div
                class="col-xl-3 col-lg-6 my-1"
              >{{ $t('randomizer.accessibility.title') }}: {{ $t(accessibility.name) }}</div>
            </div>
            <div class="row" v-if="editable">
              <div class="col-xl-6 col-lg-6 my-1">
                <Select
                  :value="glitchesRequired"
                  @input="setGlitchesRequired"
                  :options="optionsGlitchesRequired"
                >{{ $t('randomizer.glitches_required.title') }}</Select>
              </div>
              <div class="col-xl-6 col-lg-6 my-1">
                <Select
                  :value="itemPlacement"
                  @input="setItemPlacement"
                  :options="optionsItemPlacement"
                >{{ $t('randomizer.item_placement.title') }}</Select>
              </div>
              <div class="col-xl-6 col-lg-6 my-1">
                <Select
                  :value="dungeonItems"
                  @input="setDungeonItems"
                  :options="optionsDungeonItems"
                >{{ $t('randomizer.dungeon_items.title') }}</Select>
              </div>
              <div class="col-xl-6 col-lg-6 my-1">
                <Select
                  :value="accessibility"
                  @input="setAccessibility"
                  :options="optionsAccessibility"
                >{{ $t('randomizer.accessibility.title') }}</Select>
              </div>
            </div>
            <div
              v-if="glitchesRequired.value !== 'none'"
              class="logic-warning text-danger"
              v-html="$t('randomizer.glitches_required.glitch_warning')"
            />
          </div>
          <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.goal.title') }}</h5>
          <div class="card-body">
            <div class="row" v-if="!editable">
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.goal.title') }}: {{ $t(goal.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.tower_open.title') }}: {{ $t(towerOpen.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.ganon_open.title') }}: {{ $t(ganonOpen.name) }}</div>
            </div>
            <div class="row" v-if="editable">
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="goal"
                  @input="setGoal"
                  :options="optionsGoal"
                >{{ $t('randomizer.goal.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="towerOpen"
                  @input="setTowerOpen"
                  :options="optionsTowerOpen"
                >{{ $t('randomizer.tower_open.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="ganonOpen"
                  @input="setGanonOpen"
                  :options="optionsGanonOpen"
                >{{ $t('randomizer.ganon_open.title') }}</Select>
              </div>
            </div>
          </div>
          <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.gameplay.title') }}</h5>
          <div class="card-body" v-if="!editable">
            <div class="row">
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.world_state.title') }}: {{ $t(worldState.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.entrance_shuffle.title') }}: {{ $t(entranceShuffle.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.boss_shuffle.title') }}: {{ $t(bossShuffle.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.enemy_shuffle.title') }}: {{ $t(enemyShuffle.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.hints.title') }}: {{ $t(hints.name) }}</div>
            </div>
          </div>
          <div class="card-body" v-if="editable">
            <div class="row">
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="worldState"
                  @input="setWorldState"
                  :options="optionsWorldState"
                >{{ $t('randomizer.world_state.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="entranceShuffle"
                  @input="setEntranceShuffle"
                  :options="optionsEntranceShuffle"
                >{{ $t('randomizer.entrance_shuffle.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="bossShuffle"
                  @input="setBossShuffle"
                  :options="optionsBossShuffle"
                >{{ $t('randomizer.boss_shuffle.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="enemyShuffle"
                  @input="setEnemyShuffle"
                  :options="optionsEnemyShuffle"
                >{{ $t('randomizer.enemy_shuffle.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="hints"
                  @input="setHints"
                  :options="optionsHints"
                >{{ $t('randomizer.hints.title') }}</Select>
              </div>
            </div>
          </div>
          <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.difficulty.title') }}</h5>
          <div class="card-body">
            <div class="row" v-if="!editable">
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.weapons.title') }}: {{ $t(weapons.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.item_pool.title') }}: {{ $t(itemPool.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.item_functionality.title') }}: {{ $t(itemFunctionality.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.enemy_damage.title') }}: {{ $t(enemyDamage.name) }}</div>
              <div
                class="col-xl-4 col-lg-6 my-1"
              >{{ $t('randomizer.enemy_health.title') }}: {{ $t(enemyHealth.name) }}</div>
            </div>
            <div class="row" v-if="editable">
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="weapons"
                  @input="setWeapons"
                  :options="optionsWeapons"
                >{{ $t('randomizer.weapons.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select :value="itemPool" @input="setItemPool" :options="optionsItemPool">
                  {{ $t('randomizer.item_pool.title') }}
                  <sup
                    v-if="itemPool.value === 'crowd_control'"
                  >*</sup>
                </Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="itemFunctionality"
                  @input="setItemFunctionality"
                  :options="optionsItemFunctionality"
                >{{ $t('randomizer.item_functionality.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="enemyDamage"
                  @input="setEnemyDamage"
                  :options="optionsEnemyDamage"
                >{{ $t('randomizer.enemy_damage.title') }}</Select>
              </div>
              <div class="col-xl-4 col-lg-6 my-1">
                <Select
                  :value="enemyHealth"
                  @input="setEnemyHealth"
                  :options="optionsEnemyHealth"
                >{{ $t('randomizer.enemy_health.title') }}</Select>
              </div>
            </div>
            <div
              v-if="itemPool.value === 'crowd_control'"
              class="logic-warning text-info"
              v-html="$t('randomizer.item_pool.crowd_control_warning')"
            />
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md">
            <div class="btn-group btn-flex" role="group">
              <button
                class="btn btn-primary w-50 text-center"
                v-tooltip="$t('randomizer.generate.race_warning')"
                :disabled="generating"
                name="generate-tournament-rom"
                @click="applyTournamentSeed"
              >{{ $t('randomizer.generate.race') }}</button>
            </div>
          </div>
          <div class="col-md">
            <div class="btn-group btn-flex" role="group">
              <button
                class="btn btn-info w-50 text-center"
                name="generate-tournament-rom"
                :disabled="generating"
                @click="applyTournamentSpoilerSeed"
              >{{ $t('randomizer.generate.spoiler_race') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-show="generating" class="center">
      <div class="loading" />
      <h1>{{ $t('randomizer.generate.generating') }}</h1>
    </div>

    <div
      id="seed-details"
      class="card border-success"
      v-if="gameLoaded && romLoaded && !generating"
    >
      <div class="card-header bg-success text-white card-heading-btn">
        <h3 class="card-title text-white float-left">{{ $t('randomizer.details.title') }}</h3>
        <div class="btn-toolbar float-right">
          <a
            class="btn btn-light text-dark border-secondary"
            role="button"
            @click="gameLoaded = false"
          >
            {{ $t('randomizer.generate.back') }}
            <img class="icon" src="/i/svg/cog.svg" alt />
          </a>
          <a
            class="btn btn-light text-dark border-secondary ml-3"
            role="button"
            v-tooltip="$t('randomizer.generate.regenerate_tooltip')"
            @click="applySeed"
          >
            {{ $t('randomizer.generate.regenerate') }}
            <img class="icon" src="/i/svg/reload.svg" alt />
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md mb-3">
            <vt-rom-info :rom="rom"></vt-rom-info>
          </div>
          <div class="col-md mb-3">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.rom">
                  <button
                    class="btn btn-light border-secondary text-center"
                    @click="saveSpoiler"
                  >{{ $t('randomizer.details.save_spoiler') }}</button>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.rom">
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
        <vt-spoiler v-model="show_spoiler" :rom="rom"></vt-spoiler>
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from "../core/event-bus";
import FileSaver from "file-saver";
import RomLoader from "../components/VTRomLoader.vue";
import Select from "../components/Select.vue";
import localforage from "localforage";
import axios from "axios";
import { mapMutations, mapActions, mapState } from "vuex";

export default {
  components: {
    RomLoader,
    Select
  },
  data() {
    return {
      rom: null,
      error: false,
      generating: false,
      romLoaded: false,
      current_rom_hash: "",
      gameLoaded: false,
      gameGenerated: false,
      show_spoiler: false,
      tournament: false,
      disableSaveRomButton: false,
      spoilers: "off"
    };
  },
  created() {
    this.$store.dispatch("getSprites");
    this.$store.dispatch("randomizer/getItemSettings");
    this.$store.dispatch("romSettings/initialize");

    localforage.getItem("rom").then(function(blob) {
      if (blob == null) {
        EventBus.$emit("noBlob");
        return;
      }
      EventBus.$emit("loadBlob", { target: { files: [new Blob([blob])] } });
    });
  },
  methods: {
    ...mapActions("randomizer", [
      "setPreset",
      "setGlitchesRequired",
      "setItemPlacement",
      "setDungeonItems",
      "setGoal",
      "setGanonOpen",
      "setWorldState",
      "setEntranceShuffle",
      "setItemPool"
    ]),
    ...mapMutations("randomizer", [
      "setAccessibility",
      "setTowerOpen",
      "setBossShuffle",
      "setEnemyShuffle",
      "setHints",
      "setWeapons",
      "setItemFunctionality",
      "setEnemyDamage",
      "setEnemyHealth"
    ]),
    disallowSaveRom(e) {
      this.disableSaveRomButton = Boolean(e);
    },
    applyTournamentSeed() {
      this.tournament = true;
      this.spoilers = "off";
      this.applySeed();
    },
    applyTournamentSpoilerSeed() {
      this.tournament = false;
      this.spoilers = "on";
      this.applySeed();
    },
    applySeed(e, second_attempt) {
      this.error = false;
      this.generating = true;
      if (this.rom.checkMD5() != this.current_rom_hash) {
        if (second_attempt) {
          return new Promise((resolve, reject) => {
            this.generating = false;
            reject(this.rom);
          });
        }
        return this.rom
          .reset()
          .then(() => {
            return this.applySeed(e, true);
          })
          .catch(error => {
            console.error(error);
            this.generating = false;
          });
      }
      return new Promise(
        function(resolve, reject) {
          this.gameLoaded = false;
          axios
            .post(`/api/randomizer`, {
              glitches: this.glitchesRequired.value,
              item_placement: this.itemPlacement.value,
              dungeon_items: this.dungeonItems.value,
              accessibility: this.accessibility.value,
              goal: this.goal.value,
              crystals: {
                ganon: this.ganonOpen.value,
                tower: this.towerOpen.value
              },
              mode: this.worldState.value,
              entrances: this.entranceShuffle.value,
              hints: this.hints.value,
              weapons: this.weapons.value,
              item: {
                pool: this.itemPool.value,
                functionality: this.itemFunctionality.value
              },
              tournament: this.tournament,
              spoilers: this.spoilers,
              lang: document.documentElement.lang,
              enemizer: {
                boss_shuffle: this.bossShuffle.value,
                enemy_shuffle: this.enemyShuffle.value,
                enemy_damage: this.enemyDamage.value,
                enemy_health: this.enemyHealth.value
              }
            })
            .then(response => {
              let prom;
              if (this.rom.checkMD5() != this.current_rom_hash) {
                prom = this.rom.reset().then(() => {
                  if (this.rom.checkMD5() != this.current_rom_hash) {
                    return new Promise((resolve, reject) => {
                      reject(this.rom);
                    });
                  }
                  return this.rom.parsePatch(response.data);
                });
              } else {
                prom = this.rom.parsePatch(response.data);
              }
              prom.then(
                function() {
                  if (
                    response.data.current_rom_hash &&
                    response.data.current_rom_hash != this.current_rom_hash
                  ) {
                    // The base ROM has been updated.
                    window.location.reload(true);
                  }
                  if (
                    this.rom.shuffle ||
                    this.rom.spoilers == "mystery" ||
                    this.rom.allow_quickswap
                  ) {
                    this.rom.allowQuickSwap = true;
                  }
                  this.gameLoaded = true;
                  this.gameGenerated = true;
                  EventBus.$emit("gameLoaded", this.rom);
                  resolve({ rom: this.rom, patch: response.data.patch });
                }.bind(this)
              );
            })
            .catch(error => {
              if (error.response) {
                switch (error.response.status) {
                  case 429:
                    this.error = this.$i18n.t("error.429");
                    break;
                  default:
                    this.error = this.$i18n.t("error.failed_generation");
                }
              }

              reject(error);
            })
            .finally(() => {
              this.generating = false;
            });
        }.bind(this)
      );
    },
    saveRom() {
      // track the sprite choice for usage statistics
      localforage.getItem("rom.sprite-gfx").then(value => {
        ga("event", "save", {
          dimension1: value
        });
      });
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
    updateRom(rom, current_rom_hash) {
      if (!rom) {
        console.log(rom);
        return;
      }
      this.rom = rom;
      this.current_rom_hash = current_rom_hash;
      this.error = false;
      this.romLoaded = true;
    },
    onError(error) {
      this.error = error;
    }
  },
  computed: {
    ...mapState("randomizer", {
      optionsPreset: state => state.options.preset,
      preset: state => state.preset,
      optionsGlitchesRequired: state => state.options.glitches_required,
      glitchesRequired: state => state.glitches_required,
      optionsItemPlacement: state => state.options.item_placement,
      itemPlacement: state => state.item_placement,
      optionsDungeonItems: state => state.options.dungeon_items,
      dungeonItems: state => state.dungeon_items,
      optionsAccessibility: state => state.options.accessibility,
      accessibility: state => state.accessibility,
      optionsGoal: state => state.options.goal,
      goal: state => state.goal,
      optionsTowerOpen: state => state.options.tower_open,
      towerOpen: state => state.tower_open,
      optionsGanonOpen: state => state.options.ganon_open,
      ganonOpen: state => state.ganon_open,
      optionsWorldState: state => state.options.world_state,
      worldState: state => state.world_state,
      optionsEntranceShuffle: state => state.options.entrance_shuffle,
      entranceShuffle: state => state.entrance_shuffle,
      optionsBossShuffle: state => state.options.boss_shuffle,
      bossShuffle: state => state.boss_shuffle,
      optionsEnemyShuffle: state => state.options.enemy_shuffle,
      enemyShuffle: state => state.enemy_shuffle,
      optionsHints: state => state.options.hints,
      hints: state => state.hints,
      optionsWeapons: state => state.options.weapons,
      weapons: state => state.weapons,
      optionsItemPool: state => state.options.item_pool,
      itemPool: state => state.item_pool,
      optionsItemFunctionality: state => state.options.item_functionality,
      itemFunctionality: state => state.item_functionality,
      optionsEnemyDamage: state => state.options.enemy_damage,
      enemyDamage: state => state.enemy_damage,
      optionsEnemyHealth: state => state.options.enemy_health,
      enemyHealth: state => state.enemy_health
    }),
    ...mapState("romSettings", {
      heartSpeed: state => state.heartSpeed,
      menuSpeed: state => state.menuSpeed,
      heartColor: state => state.heartColor,
      quickswap: state => state.quickswap,
      musicOn: state => state.musicOn,
      paletteShuffle: state => state.paletteShuffle,
      reduceFlashing: state => state.reduceFlashing
    }),
    editable() {
      return this.$store.state.randomizer.preset.value === "custom";
    }
  }
};
</script>
