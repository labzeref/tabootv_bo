<script setup>
import {onBeforeUnmount, onMounted, onUnmounted, watch} from "vue";
import {defineAsyncComponent, ref} from "vue";
import {formatDuration} from "../utils.js";
import {usePage} from "@inertiajs/vue3";
import forwardIcon from '@/assets/forward-30.svg'
import replayIcon from '@/assets/rewind-110.svg'
import airPlayIcon from '@/assets/airplay.svg'
import gearIcon from '@/assets/gear-confi.svg'

const Spinner = defineAsyncComponent(() => import('@/Components/Spinner.vue'))
const PauseSvg = defineAsyncComponent(() => import('@/Components/svg/videoPlayer/pause.vue'));
const PlayingSvg = defineAsyncComponent(() => import('@/Components/svg/videoPlayer/play.vue'));
const FullScreenSvg = defineAsyncComponent(() => import('@/Components/svg/videoPlayer/fullScreen.vue'));
const SmallScreenSvg = defineAsyncComponent(() => import('@/Components/svg/videoPlayer/smallScreen.vue'));
const VolumeSvg = defineAsyncComponent(() => import('@/Components/svg/videoPlayer/volume.vue'));
const MuteSvg = defineAsyncComponent(() => import('@/Components/svg/videoPlayer/Mute.vue'));
const VideoPlayerBtnSvg = defineAsyncComponent(() => import('@/Components/svg/icons/videoPlayerBtn.vue'));

const emit = defineEmits(['ended', 'progress']);

const props = defineProps({
    autoplay: {
        type: Boolean,
        default: false
    },
    cc: {
        type: [Array, null]
    },
    url_1440: {
        type: String,
        default: null,
    },
    url_1080: {
        type: String,
        default: null,
    },
    url_720: {
        type: String,
        default: null,
    },
    url_480: {
        type: String,
        default: null,
    },
    thumbnail: {
        type: String,
        default: '',
        required: false,
    }
})

const videoPlayerRef = ref(null);
const caption = ref(null);
const showCaption = ref(true);
const controlTimer = ref(null);
const showControls = ref(false);
const progress = ref(0);
const volume = ref(1);
const spinner = ref(false);
const fullscreen = ref(false)
const showThumbnail = ref(true)
const autoplay = ref(false);
const isVideoPause = ref(true);
const previousVolume = ref(1);

/**
 * Handle Events
 */

const handleTimeUpdate = (event) => {
    updateProgress(event);
};

const handleClick = () => {
    togglePausePlayVideo();
};

const handleMouseDown = (event) => {
    setProgress(event);
};

/**
 * Methods
 */

 const setVolume = (event) => {
    const volumeValue = parseFloat(event.target.value); // Ensure it's a number
    videoPlayerRef.value.volume = volumeValue; // Set the video player's volume
    volume.value = volumeValue; // Update the reactive volume value
    previousVolume.value = volumeValue; // Update the previous volume
};

const setToggleMute = () => {
    if (volume.value !== 0) {
        // Mute the video
        previousVolume.value = volume.value; // Store the current volume
        videoPlayerRef.value.volume = 0; // Mute the video
        volume.value = 0; // Update the reactive volume value
    } else {
        // Unmute the video
        videoPlayerRef.value.volume = previousVolume.value; // Restore the volume
        volume.value = previousVolume.value; // Update the reactive volume value
    }
};

 const airPlay = () => {
    if (videoPlayerRef.value.webkitShowPlaybackTargetPicker) {
        videoPlayerRef.value.webkitShowPlaybackTargetPicker();
  } else {
    alert('AirPlay is not supported in this browser.');
  }
 }

const togglePausePlayVideo = () => {
    if (videoPlayerRef.value.paused) {
        isVideoPause.value = false;
        videoPlayerRef.value.play();
    } else {
        isVideoPause.value = true;
        videoPlayerRef.value.pause();
    }
};

const handleKeydown = (event) => {
  if (event.code === "Space") {
    event.preventDefault(); // Prevent scrolling when Space is pressed
    togglePausePlayVideo();
  }
};

onMounted(() => {
  window.addEventListener("keydown", handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener("keydown", handleKeydown);
});


const toggleScreenSize = () => {
    if (document.fullscreenElement) {
        exitFullscreen();
    } else {
        enterFullscreen();
    }
};

const setProgress = (event) => {
    const targetWidthPercentage = getClickPositionPercentage(event);
    videoPlayerRef.value.currentTime = (videoPlayerRef.value.duration / 100) * targetWidthPercentage;
};

// ---------------------------FORWARD REWIND-----------------------------------------
const setProgressForwardTen = (event) => {
    const targetWidthPercentage = getClickPositionPercentage(event);
    videoPlayerRef.value.currentTime = videoPlayerRef.value.currentTime + 30;
};

const setProgressPrevTen = (event) => {
    const targetWidthPercentage = getClickPositionPercentage(event);
    videoPlayerRef.value.currentTime = videoPlayerRef.value.currentTime - 10;
};



const handleKeyDown = (event) => {
  if (!videoPlayerRef.value) return;

  switch (event.key) {
    case 'ArrowRight': // Forward
      videoPlayerRef.value.currentTime += 30;
      break;
    case 'ArrowLeft': // Rewind
      videoPlayerRef.value.currentTime -= 10;
      break;
  }
};

// Attach and detach the event listener for arrow keys
onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});

// ---------------------------FORWARD REWIND-----------------------------------------




const playVideoByThumbnail = () => {
    isVideoPause.value = false;
    togglePausePlayVideo();
    showThumbnail.value = false;
}

const enableControls = () => {
    if (showControls.value) {
        return;
    }

    clearTimeout(controlTimer.value);
    showControls.value = true;
}

const disableControls = () => {
    controlTimer.value = setTimeout(() => {
        showControls.value = false;
    }, 2000);
};

/**
 * Utilities
 */

const updateProgress = (event) => {
    const currentTime = event.target.currentTime;
    const duration = event.target.duration;
    progress.value = (currentTime / duration) * 100;
    emit('progress', progress.value);
};

const enterFullscreen = () => {
    document.body.style.position = 'fixed';
    const element = document.documentElement;

    if (element.requestFullscreen) {
        element.requestFullscreen();
    } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) {
        element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) {
        element.msRequestFullscreen();
    }
};

const exitFullscreen = () => {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
};

const getClickPositionPercentage = (event) => {
    const progressBar = event.target;
    const rect = progressBar.getBoundingClientRect();
    const targetWidth = event.clientX - rect.left; // with where the mouse is clicked on progress bar
    return (targetWidth / rect.width) * 100; // percentage where the mouse is clicked on progress bar
}

onMounted(() => {
    autoplay.value = usePage().props?.auth?.user?.video_autoplay;

    // if (autoplay.value) {
    //     showThumbnail.value = false;
    // }

    document.addEventListener('fullscreenchange', () => {
        fullscreen.value = Boolean(document.fullscreenElement);
        if (!fullscreen.value) {
            document.body.style.position = '';
        }
    })
})

onBeforeUnmount(() => {
    document.removeEventListener('fullscreenchange', () => {
        fullscreen.value = Boolean(document.fullscreenElement);
        if (!fullscreen.value) {
            document.body.style.position = '';
        }
    })
})

watch(progress, () => {
    if (showCaption && props.cc) {
        const currentVideoTime = videoPlayerRef.value.currentTime;
        const obj = props.cc.find(item => item.startTime <= currentVideoTime && item.endTime >= currentVideoTime);
        if (obj) {
            caption.value = obj.text;
        } else {
            caption.value = null;
        }
    } else {
        caption.value = null;
    }
})

defineExpose({
    togglePausePlayVideo
})




const dropdownVisible = ref(false);

// Available video qualities
const availableQualities = ref([
  { label: '480p', url: 'path/to/video_480.mp4' },
  { label: '720p', url: 'path/to/video_720.mp4' },
  { label: '1080p', url: 'path/to/video_1080.mp4' },
  { label: '1440p', url: 'path/to/video_1440.mp4' },
]);

// Currently selected quality
const selectedQuality = ref(availableQualities.value[0]); // Default to 480p

// Toggle dropdown visibility
const toggleDropdown = () => {
  dropdownVisible.value = !dropdownVisible.value;
};

// Select a quality and update the video source
const selectQuality = (quality) => {
  selectedQuality.value = quality;
  dropdownVisible.value = false; // Close the dropdown
};
</script>

<template>
    <div
        class="w-full h-full bg-black aspect-video group"
        :class="{'fixed inset-0 z-[500]': fullscreen, 'rounded-[30px] overflow-hidden relative z-[10]': !fullscreen}"
    >
        <div v-if="showThumbnail" class="absolute inset-0 z-[10]">
            <img class="size-full" :src="thumbnail" alt="">
            <VideoPlayerBtnSvg class="absolute"   @click="playVideoByThumbnail"/>
        </div>
        <Transition name="fade">
            <div v-if="showControls || isVideoPause" class="absolute forward-perv-btn hidden group-hover:block">
                <div class="max-forword-btn">
                    <img :src="replayIcon" alt="" @click="setProgressPrevTen" class="play-pause-next-perv cursor-pointer filter-white">
                    <div>
                        <PlayingSvg class="cursor-pointer play-pause-next-perv" v-if="isVideoPause" @click="togglePausePlayVideo"/>
                        <PauseSvg class="cursor-pointer play-pause-next-perv" v-else @click="togglePausePlayVideo"/>
                    </div>
                        <img :src="forwardIcon" @click="setProgressForwardTen" alt="" class="play-pause-next-perv cursor-pointer filter-white">
                    </div>
            </div>
        </Transition>

        <video
            class="w-full h-full bg-lightBlack"
            preload="metadata"
            ref="videoPlayerRef"
            @click="handleClick"
            @timeupdate="handleTimeUpdate"
            @seeking="spinner=true"
            @seeked="spinner=false"
            @ended="$emit('ended')"
            @mouseover="enableControls"
            @mouseleave="disableControls"
            playsinline
            webkit-playsinline
        >
            <source v-if="url_480" :src="url_480"
                    type="video/mp4">
            <source v-if="url_720" :src="url_720"
                    type="video/mp4">
            <source v-if="url_1080" :src="url_1080"
                    type="video/mp4">
            <source v-if="url_1440" :src="url_1440"
                    type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <Transition name="fade">
            <div
                v-if="showControls || isVideoPause"
                class="h-[60px] absolute bottom-0 bg-transparent w-full p-3 flex flex-col space-y-2"
                style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.25) 100%)"
            >
                <div
                    class="h-[5px] w-full flex items-center cursor-pointer"
                    @mousedown="handleMouseDown($event)"
                >
                    <div class="h-[5px] bg-[#ff3a44] pointer-events-none" :style="`width: ${progress}%;`"></div>
                    <div class="h-[5px] pointer-events-none"
                         :style="`width: ${100-progress}%;background-color: rgba(255, 255, 255, 0.25);`"></div>
                </div>

                <div class="h-[22px] w-full flex items-center justify-between space-x-5">
                    <div class="h-full flex items-center space-x-5">
                        <PlayingSvg class="cursor-pointer" v-if="isVideoPause" @click="togglePausePlayVideo"/>
                        <PauseSvg class="cursor-pointer" v-else @click="togglePausePlayVideo"/>

                        <div class="h-full flex items-center space-x-2">
                            <p>{{ formatDuration(videoPlayerRef?.currentTime) }}</p>
                            <p>/</p>
                            <p>{{ formatDuration(videoPlayerRef?.duration) }}</p>
                        </div>
                    </div>
                    <div class="h-full flex items-center space-x-4">
                        <img :src="airPlayIcon" class="cursor-pointer h-6 w-6" @click="airPlay" alt="">
                        <!-- ---------------------------VIDEO QUALITY SELECT----------------------------------  -->
                        <div class="relative">
                                    <!-- Gear Icon -->
                                    <img
                                    :src="gearIcon"
                                    class="cursor-pointer h-6 w-6"
                                    alt="Settings"
                                    @click="toggleDropdown"
                                    />

                                    <!-- Dropdown -->
                                    <div class="absolute top-[-10.5rem] right-[-5rem] z-10 bg-[#AB0013] divide-y divide-gray-100 rounded-lg shadow w-32"
                                        v-if="dropdownVisible"
                                    >

                                    <ul class="py-2 text-sm text-white z-20 relative" aria-labelledby="dropdownTopButton">
                                        <li v-for="quality in availableQualities"
                                        :key="quality.label"
                                        @click="selectQuality(quality)"
                                    >
                                      <a href="#" class="block px-4 py-2 hover:bg-[#4b2c30ab]">{{ quality.label }}</a>
                                    </li>
                                  </ul>
                                  <div @click='toggleDropdown' class="inset-0 fixed w-full h-full"></div>
                            </div>
                        </div>
                    <!-- -------------------------------VIDEO QUALITY SELECT------------------------------  -->
                        <div class="flex items-center space-x-2">
                            <VolumeSvg v-if="volume !== 0" @click="setToggleMute" class="cursor-pointer"/>
                            <MuteSvg v-else @click="setToggleMute"  class="cursor-pointer"/>
                            <input
                                type="range"
                                min="0"
                                max="1"
                                step="0.1"
                                :value="volume"
                                @input="setVolume($event)"
                                :style="`background: linear-gradient(to right, #AB0013 ${volume*100}%,rgba(255, 255, 255, 0.25) 10%);`"
                                class="volume-range appearance-none w-[50px] outline-none rounded-full h-1.5"
                            />
                        </div>

                        <SmallScreenSvg v-if="fullscreen" class="cursor-pointer" @click="toggleScreenSize"/>
                        <FullScreenSvg v-else class="cursor-pointer" @click="toggleScreenSize"/>
                    </div>
                </div>
            </div>
        </Transition>

        <div
            v-if="spinner"
            class="absolute h-full w-full top-0 left-0 flex justify-center items-center pointer-events-none"
            style="background: rgb(0, 0, 0, 0.3)"
        >
            <Spinner/>
        </div>
    </div>
</template>

<style scoped>
video::-webkit-media-controls {
    display: none !important;
}
video::-webkit-media-controls-start-playback-button {
    display: none !important;
    opacity: 0;
    pointer-events: none;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.filter-white{
    filter: drop-shadow(5px 5px 10px rgba(232, 232, 232, 0.9));
}
</style>
