import SparkMD5 from "spark-md5";
import FileSaver from "file-saver";
import Prando from "prando";
import BPS from "./bps";
import * as Z3PR from "@maseya/z3pr";
import localforage from "localforage";
import center from "center-align";

export default class ROM {
  constructor(blob, loadedCallback) {
    this.u_array = [];
    this.arrayBuffer;
    this.originalData;
    this.size = 2;

    const fileReader = new FileReader();

    fileReader.onload = event => {
      this.arrayBuffer = event.target.result;
    };

    fileReader.onloadend = () => {
      if (typeof this.arrayBuffer === "undefined") {
        throw new Error("Could not read this.arrayBuffer");
      }
      // Check ROM for header and cut it out
      if (this.arrayBuffer.byteLength % 0x400 == 0x200) {
        this.arrayBuffer = this.arrayBuffer.slice(
          0x200,
          this.arrayBuffer.byteLength
        );
      }

      this.originalData = this.arrayBuffer.slice(0);
      this.resize(this.size);

      this.u_array = new Uint8Array(this.arrayBuffer);

      localforage.getItem("vt_sprites.001.link.1.zspr").then(spr => {
        if (spr) {
          return;
        }
        const source = new Uint8Array(this.originalData);
        const orginal = new ArrayBuffer(0x70BE);
        const u_original = new Uint8Array(orginal);
        const header = [
          0x5A, 0x53, 0x50, 0x52, 0x01, 0x07, 0xB1, 0xF8,
          0x4E, 0x42, 0x00, 0x00, 0x00, 0x00, 0x70, 0x42,
          0x70, 0x00, 0x00, 0x7C, 0x00, 0x01, 0x00, 0x00,
          0x00, 0x00, 0x00, 0x00, 0x00, 0x4C, 0x00, 0x69,
          0x00, 0x6E, 0x00, 0x6B, 0x00, 0x00, 0x00, 0x4E,
          0x00, 0x69, 0x00, 0x6E, 0x00, 0x74, 0x00, 0x65,
          0x00, 0x6E, 0x00, 0x64, 0x00, 0x6F, 0x00, 0x00,
          0x00, 0x4E, 0x69, 0x6E, 0x74, 0x65, 0x6E, 0x64,
          0x6F, 0x00,
        ];
        header.forEach((v, i) => {
          u_original[i] = v;
        });
        for (let i = 0; i < 0x7000; i++) {
          u_original[0x42 + i] = source[0x80000 + i];
        }
        for (let i = 0; i < 120; i++) {
          u_original[0x7042 + i] = source[0xdd308 + i];
        }
        for (let i = 0; i < 4; ++i) {
          u_original[0x7042 + 120 + i] = source[0xdedf5 + i];
        }

        localforage.setItem("vt_sprites.001.link.1.zspr", u_original)
      });



      if (loadedCallback) loadedCallback(this);
    };

    fileReader.readAsArrayBuffer(blob);
  }

  checkMD5() {
    return SparkMD5.ArrayBuffer.hash(this.arrayBuffer);
  }

  getOriginalArrayBuffer() {
    return this.originalData;
  }

  write(seek, bytes) {
    if (!Array.isArray(bytes)) {
      this.u_array[seek] = bytes;
      return;
    }
    for (var i = 0; i < bytes.length; i++) {
      this.u_array[seek + i] = bytes[i];
    }
  }

  updateChecksum() {
    return new Promise(resolve => {
      var sum = this.u_array.reduce((sum, mbyte, i) => {
        if (i >= 0x7fdc && i < 0x7fe0) {
          return sum;
        }
        return sum + mbyte;
      });
      var checksum = (sum + 0x1fe) & 0xffff;
      var inverse = checksum ^ 0xffff;
      this.write(0x7fdc, [
        inverse & 0xff,
        inverse >> 8,
        checksum & 0xff,
        checksum >> 8
      ]);
      resolve(this);
    });
  }

  save(filename, { paletteShuffle, quickswap, musicOn, reduceFlashing }) {
    let preProcess = this.arrayBuffer.slice(0);

    if (paletteShuffle) {
      this.randomizePalettes();
    }
    if (!this.tournament || this.allowQuickSwap) {
      this.setQuickswap(quickswap);
    } else {
      this.setQuickswap(false);
    }
    this.setMusicVolume(musicOn);

    this.setReduceFlashing(reduceFlashing);

    this.updateChecksum().then(() => {
      FileSaver.saveAs(new Blob([this.u_array], { type: 'application/octet-stream' }), filename);

      // undo any presave processing we did.
      this.arrayBuffer = preProcess;
      this.u_array = new Uint8Array(this.arrayBuffer);
    });
  }

  parseSprGfx(spr) {
    const headBytes =
      String.fromCharCode(spr[0]) +
      String.fromCharCode(spr[1]) +
      String.fromCharCode(spr[2]) +
      String.fromCharCode(spr[3]);

    if ("ZSPR" == headBytes) {
      return this.parseZsprGfx(spr);
    }

    return new Promise(resolve => {
      for (let i = 0; i < 0x7000; i++) {
        this.u_array[0x80000 + i] = spr[i];
      }
      for (let i = 0; i < 120; i++) {
        this.u_array[0xdd308 + i] = spr[0x7000 + i];
      }
      // gloves color
      this.u_array[0xdedf5] = spr[0x7036];
      this.u_array[0xdedf6] = spr[0x7037];
      this.u_array[0xdedf7] = spr[0x7054];
      this.u_array[0xdedf8] = spr[0x7055];
      resolve(this);
    });
  }

  // we are going to just hope that it's in the proper format O.o
  parseZsprGfx(zspr) {
    return new Promise(resolve => {
      const gfx_offset =
        (zspr[12] << 24) | (zspr[11] << 16) | (zspr[10] << 8) | zspr[9];
      const palette_offset =
        (zspr[18] << 24) | (zspr[17] << 16) | (zspr[16] << 8) | zspr[15];

      // ZSPR Metadata
      var metadata_index = 0x1D;

      var sprite_author_short = "";

      // skip past unicode title and author
      let junk = 2;
      while (metadata_index < gfx_offset && junk > 0) {
        if (zspr[metadata_index + 1] === 0 && zspr[metadata_index] === 0) {
          junk--;
        }
        metadata_index = metadata_index + 2;
      }

      while (metadata_index < gfx_offset && zspr[metadata_index] !== 0x00) {
        sprite_author_short += String.fromCharCode(zspr[metadata_index]);
        metadata_index++;
      }

      var formatted_sprite_author = center(sprite_author_short.substring(0, 28), 28).toUpperCase();
      if (formatted_sprite_author.length == 27) {
        formatted_sprite_author = formatted_sprite_author + " ";
      }

      const sprite_author = formatted_sprite_author.split("").map(item => {
          switch (item) {
              case " ": return [0x9F, 0x9F];
              case "0": return [0x53, 0x79];
              case "1": return [0x54, 0x7A];
              case "2": return [0x55, 0x7B];
              case "3": return [0x56, 0x7C];
              case "4": return [0x57, 0x7D];
              case "5": return [0x58, 0x7E];
              case "6": return [0x59, 0x7F];
              case "7": return [0x5A, 0x80];
              case "8": return [0x5B, 0x81];
              case "9": return [0x5C, 0x82];
              case "A": return [0x5D, 0x83];
              case "B": return [0x5E, 0x84];
              case "C": return [0x5F, 0x85];
              case "D": return [0x60, 0x86];
              case "E": return [0x61, 0x87];
              case "F": return [0x62, 0x88];
              case "G": return [0x63, 0x89];
              case "H": return [0x64, 0x8A];
              case "I": return [0x65, 0x8B];
              case "J": return [0x66, 0x8C];
              case "K": return [0x67, 0x8D];
              case "L": return [0x68, 0x8E];
              case "M": return [0x69, 0x8F];
              case "N": return [0x6A, 0x90];
              case "O": return [0x6B, 0x91];
              case "P": return [0x6C, 0x92];
              case "Q": return [0x6D, 0x93];
              case "R": return [0x6E, 0x94];
              case "S": return [0x6F, 0x95];
              case "T": return [0x70, 0x96];
              case "U": return [0x71, 0x97];
              case "V": return [0x72, 0x98];
              case "W": return [0x73, 0x99];
              case "X": return [0x74, 0x9A];
              case "Y": return [0x75, 0x9B];
              case "Z": return [0x76, 0x9C];
              case "'": return [0x77, 0x9d];
              case ".": return [0xA0, 0xC0];
              case "/": return [0xA2, 0xC2];
              case ":": return [0xA3, 0xC3];
              case "_": return [0xA6, 0xC6];
              default: return [0x9F, 0x9F];
          }
      });

      // Do not write sprite author to older rom builds, or the game will crash.
      // This checks for the line header bytes are what we expect, so we're not
      // inadvertently writing over executable code that was relocated from it's
      // vanilla location.
      if (this.u_array[0x118000] === 0x02
          && this.u_array[0x118001] === 0x37
          && this.u_array[0x11801E] === 0x02
          && this.u_array[0x11801F] === 0x37) {
        for (let i = 0; i < 28; i++) {
          this.u_array[0x118002 + i] = sprite_author[i][0];
          this.u_array[0x118020 + i] = sprite_author[i][1];
        }
      }

      // GFX
      for (let i = 0; i < 0x7000; i++) {
        this.u_array[0x80000 + i] = zspr[gfx_offset + i];
      }

      // Palettes
      for (let i = 0; i < 120; i++) {
        this.u_array[0xdd308 + i] = zspr[palette_offset + i];
      }

      // Gloves
      for (let i = 0; i < 4; ++i) {
        this.u_array[0xdedf5 + i] = zspr[palette_offset + 120 + i];
      }

      resolve(this);
    });
  }

  setQuickswap(enable) {
    return new Promise(resolve => {
      this.write(0x18004b, enable ? 0x01 : 0x00);

      resolve(this);
    });
  }

  setMusicVolume(enable) {
    return new Promise(resolve => {
      if (this.build > "2019-08-01") {
        this.write(0x18021a, !enable ? 0x01 : 0x00);
      } else {
        this.write(0x0cfe18, !enable ? 0x00 : 0x70);
        this.write(0x0cfec1, !enable ? 0x00 : 0xc0);
        this.write(0x0d0000, !enable ? [0x00, 0x00] : [0xda, 0x58]);
        this.write(0x0d00e7, !enable ? [0xc4, 0x58] : [0xda, 0x58]);
      }

      resolve(this);
    });
  }

  setMenuSpeed(speed) {
    return new Promise(resolve => {
      let fast = false;
      switch (speed) {
        case "instant":
          this.write(0x180048, 0xe8);
          fast = true;

          break;
        case "fast":
          this.write(0x180048, 0x10);

          break;
        case "normal":
        default:
          this.write(0x180048, 0x08);

          break;
        case "slow":
          this.write(0x180048, 0x04);

          break;
      }
      this.write(0x6dd9a, fast ? 0x20 : 0x11);
      this.write(0x6df2a, fast ? 0x20 : 0x12);
      this.write(0x6e0e9, fast ? 0x20 : 0x12);

      resolve(this);
    });
  }

  setHeartColor(color_on) {
    return new Promise(resolve => {
      let byte = 0x24;
      let file_byte = 0x05;

      if (color_on === "random") {
        const colorOptions = ["blue", "green", "yellow", "red"];
        color_on = colorOptions[Math.floor(Math.random() * colorOptions.length)];
      };

      switch (color_on) {
        case "blue":
          byte = 0x2c;
          file_byte = 0x0d;

          break;
        case "green":
          byte = 0x3c;
          file_byte = 0x19;

          break;
        case "yellow":
          byte = 0x28;
          file_byte = 0x09;

          break;
        case "red":
        default:
        // do nothing
      }

      this.write(0x6fa1e, byte);
      this.write(0x6fa20, byte);
      this.write(0x6fa22, byte);
      this.write(0x6fa24, byte);
      this.write(0x6fa26, byte);
      this.write(0x6fa28, byte);
      this.write(0x6fa2a, byte);
      this.write(0x6fa2c, byte);
      this.write(0x6fa2e, byte);
      this.write(0x6fa30, byte);
      this.write(0x65561, file_byte);

      resolve(this);
    });
  }

  setHeartSpeed(speed) {
    return new Promise(resolve => {
      let sbyte = 0x20;
      switch (speed) {
        case "off":
          sbyte = 0x00;

          break;
        case "half":
          sbyte = 0x40;

          break;
        case "quarter":
          sbyte = 0x80;

          break;
        case "double":
          sbyte = 0x10;

          break;
      }
      this.write(0x180033, sbyte);

      resolve(this);
    });
  }

  randomizePalettes() {
    Z3PR.randomize(this.u_array, {
      mode: 'maseya',
      randomize_overworld: true,
      randomize_dungeon: true,
      seed: this.rand.nextInt(0, 4294967295)
    });
    this.rand.reset()
  }

  setReduceFlashing(enable) {
    return new Promise(resolve => {
      if (this.build >= "2021-05-04") {
        this.write(0x18017f, enable ? 0x01 : 0x00);
      }

      resolve(this);
    });
  }

  parsePatch(data, progressCallback) {
    return new Promise(resolve => {
      this.rand = new Prando(data.hash);
      this.seed = data.seed;
      this.spoiler = data.spoiler;
      this.hash = data.hash;
      this.generated = data.generated;

      if (data.size) {
        this.resize(data.size);
      }

      if (data.spoiler && data.spoiler.meta) {
        this.accessibility = data.spoiler.meta.accessibility;
        this.build = data.spoiler.meta.build;
        this.goal = data.spoiler.meta.goal;
        this.logic = data.spoiler.meta.logic;
        this.mode = data.spoiler.meta.mode;
        this.name = data.spoiler.meta.name;
        this.variation = data.spoiler.meta.variation;
        this.weapons = data.spoiler.meta.weapons;
        this.shuffle = data.spoiler.meta.shuffle;
        this.difficulty_mode = data.spoiler.meta.difficulty_mode;
        this.difficulty = data.spoiler.meta.difficulty;
        this.notes = data.spoiler.meta.notes;
        this.tournament = data.spoiler.meta.tournament;
        this.spoilers = data.spoiler.meta.spoilers;
        this.allow_quickswap = data.spoiler.meta.allow_quickswap;
        this.special = data.spoiler.meta.special;
      }

      if (data.patch && data.patch.length) {
        data.patch.forEach((value, index) => {
          if (progressCallback)
            progressCallback(index / data.patch.length, this);
          for (let address in value) {
            this.write(Number(address), value[address]);
          }
        });
      }

      resolve(this);
    });
  }

  parseBaseBPS(bps) {
    return new Promise((resolve, reject) => {
      const patcher = new BPS();

      patcher.setPatch(bps);
      patcher.setSource(this.originalData);

      try {
        this.arrayBuffer = patcher.applyPatch();
      } catch (error) {
        reject(error);
      }

      resolve(this);
    });
  }

  setBaseBPS(patch) {
    this.baseBPS = patch;
  }

  resizeUint8(baseArrayBuffer, newByteSize) {
    var resizedArrayBuffer = new ArrayBuffer(newByteSize),
      len = baseArrayBuffer.byteLength,
      resizeLen = len > newByteSize ? newByteSize : len;

    new Uint8Array(resizedArrayBuffer, 0, resizeLen).set(
      new Uint8Array(baseArrayBuffer, 0, resizeLen)
    );

    return resizedArrayBuffer;
  }

  resize(size) {
    switch (size) {
      case 4:
        this.arrayBuffer = this.resizeUint8(this.arrayBuffer, 4194304);
        break;
      case 2:
        this.arrayBuffer = this.resizeUint8(this.arrayBuffer, 2097152);
        break;
      case 1:
      default:
        size = 1;
        this.arrayBuffer = this.resizeUint8(this.arrayBuffer, 1048576);
    }
    this.u_array = new Uint8Array(this.arrayBuffer);
    this.size = size;
  }

  downloadFilename() {
    if (this.name) {
      return "alttpr - " + this.name + "_" + this.hash;
    } else if (this.spoilers == "mystery") {
      return "alttpr - mystery_" + this.hash;
    } else {
      return (
        "alttpr - " +
        this.logic +
        "-" +
        this.mode +
        "-" +
        this.goal +
        "_" +
        this.hash +
        (this.special ? "_special" : "")
      );
    }
  }

  reset() {
    return new Promise((resolve, reject) => {
      this.arrayBuffer = this.originalData.slice(0);
      // always reset to 2mb so we can verify MD5 later
      this.resize(2);

      if (!this.baseBPS) {
        reject("base patch not set");
      }
      this.parseBaseBPS(this.baseBPS)
        .then(rom => {
          resolve(rom);
        })
        .catch(error => {
          console.log(error, ":(");
          reject("sadness");
        });
    });
  }
}
