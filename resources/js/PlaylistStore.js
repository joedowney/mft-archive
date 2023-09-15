import PlayerStore from "@/PlayerStore.js"
import {computed, ref, watch} from "vue";

let x = null;

watch(PlayerStore.state.band, (newValue) => {
    x.value = newValue;
});
export default {
    x: computed(() => x)
}

// let songs = computed(() => {
//     let songs = [];
//     PlayerStore.currentBand.value?.albums.forEach((album) => {
//         album.songs.forEach((song) => songs.push(song));
//     });
//     return songs;
// });
//
// let currentIndex = computed(() => {
//     return 'hello';
    let current_song_in_list = songs.value.find((song) => song.ID === PlayerStore.currentSong.value.ID);
    return songs.value.indexOf(current_song_in_list);
// });

// let currentIndex = () => {
//     let current_song_in_list = songs.value.find((song) => song.ID === PlayerStore.currentSong.value.ID);
//     return songs.value.indexOf(current_song_in_list);
// };

// let next = computed(() => {
//     return 'next';
// });
//
// let prev = computed(() => {
//     return 'prev';
// })
//
//
// export default {
//     songs: songs,
//     next: next,
//     prev: computed(() => {
//         return 'prev';
//     }),
//     currentIndex: currentIndex
// }
