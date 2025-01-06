<script setup>
import Cross from "@/Components/svg/icons/cross.vue";
import Filter from "@/Components/svg/icons/filter.vue";
import VideoCard from "@/Pages/LiveChat/Partials/VideoCard.vue";
import VuetifyDropdown from "@/Components/VuetifyDropdown.vue";
import img2 from "@/images/cards/img2.png";
import img1 from "@/images/cards/img1.png";
import img3 from "@/images/cards/img3.png";
import img4 from "@/images/cards/img4.png";
import img6 from "@/images/cards/img6.png";
import img5 from "@/images/cards/img5.png";
import {ref} from "vue";

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
})

const videos = [
    {
        id: 1,
        img: img2,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '20 minutes ago',
        channel: 'Arabuncut',
    },
    {
        id: 2,
        img: img1,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '1 day ago',
        channel: 'Arabuncut',
    },
    {
        id: 3,
        img: img3,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '12 days ago',
        channel: 'Arabuncut',
    },
    {
        id: 4,
        img: img4,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '3 days ago',
        channel: 'Arabuncut',
    },
    {
        id: 5,
        img: img2,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '2 days ago',
        channel: 'Arabuncut',
    },
    {
        id: 6,
        img: img1,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '7 days ago',
        channel: 'Arabuncut',
    },
    {
        id: 7,
        img: img6,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '7 days ago',
        channel: 'Arabuncut',
    },
    {
        id: 8,
        img: img5,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '7 days ago',
        channel: 'Arabuncut',
    },
    {
        id: 9,
        img: img2,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '20 minutes ago',
        channel: 'Arabuncut',
    },
    {
        id: 10,
        img: img1,
        duration: '12:26',
        title: 'Lorem ipsum dolor sit amet',
        posted_at: '1 day ago',
        channel: 'Arabuncut',
    },
]

const videosFilter = ref(false);
const seriesFilter = ref(true);




const countries = ['USA', 'CANADA', 'PAK']
const sortBy = ['Newest post', 'Trending', 'Old']
const isFiltered = ref(false)

const tags = ['Journalism', 'Travel', 'Food', 'Danger', 'Adventure', 'Journalism', 'Travel', 'Food', 'Danger', 'Adventure', 'Journalism', 'Travel', 'Food']

const activateVideos = () => {
    videosFilter.value = true;
    seriesFilter.value = false
}

const activateSeries = () => {
    seriesFilter.value = true;
    videosFilter.value = false
}

function toggleSelection({id, checked}) {
    console.log('working')
    if (checked) {
        selectedImageIds.value.push(id)
    } else {
        selectedImageIds.value = selectedImageIds.value.filter(item => item !== id)
    }
}
</script>

<template>
<div :class="open ? 'w-full visible' : 'w-0 border-none invisible'"
                     class="sendFile absolute z-50 right-0 top-0 bottom-0 bg-black flex flex-col transition-all duration-500 justify-start items-center border-r border-black rounded-l-[30px]">
                    <div class="overlay flex">
                        <v-btn color="primaryDark" height="44px" width="325px"
                               rounded='pill' class="mx-auto">Send (0)
                        </v-btn>
                    </div>

                    <div class="w-full overflow-hidden bg-primaryDark md:py-4 px-7 flex justify-between items-center">
                        <span class="min-w-fit">Share Video</span>

                        <v-btn :icon="Cross" color="transparent" style="box-shadow: none" @click="sendFile = false"/>
                    </div>

                    <div class="w-full p-1 max-w-[460px] space-y-1 md:space-y-[16px] mt-1 md:mt-6">
                        <div class="flex w-full mb-3">
                            <button @click="activateVideos()" :class="videosFilter ? 'bg-white' : 'bg-[#201F1F]'"
                                    class="w-full p-1 rounded-l-3xl ring-0">Videos
                            </button>
                            <button @click="activateSeries()" :class="seriesFilter ? 'bg-white' : 'bg-[#201F1F]'"
                                    class="w-full p-1 rounded-r-3xl ring-0">Series
                            </button>
                        </div>

                        <input
                            class="w-full h-[38px] py-3 px-4 rounded-lg text-secondary placeholder-secondary bg-[#4742424D] focus:ring-white text-[14px] border border-secondary"
                            type="text" :placeholder="videosFilter ? 'Search Video..' : 'Search Series..' ">

                        <h6>All Videos</h6>
                        <div class="flex w-full gap-[15px] items-center justify-center">
                            <VuetifyDropdown :list="countries" label="Country" placeholder="USA"/>

                            <VuetifyDropdown :list="sortBy" label="Sort by" placeholder="Newest Post"/>

                            <v-btn tile :color="isFiltered ? '#AB0013' : '#4742424D'"
                                   :class="isFiltered && 'bg-[#AB0013]'" :rounded="false" class="rounded-lg"
                                   style="width: 38px;"
                                   height="38"
                                   @click="() => isFiltered = !isFiltered">
                                <Filter :active="isFiltered"/>
                            </v-btn>
                        </div>

                        <v-slide-y-transition>
                            <div v-if="isFiltered" class="flex flex-wrap gap-[6px] md:gap-[10px] w-full justify-end">
                                <v-btn height="34" rounded="pill" style="font-size: 12px"
                                       v-for="(tag, key) in tags" :key>
                                    {{ tag }}
                                </v-btn>
                            </div>
                        </v-slide-y-transition>
                    </div>

                    <div class="h-full w-full overflow-auto p-2 md:px-[30px] py-3 space-y-3 relative my-2">
                        <video-card
                            v-for="(object, index) in videos"
                            :key="object.id"
                            :video="object"
                            @toggleSelection="toggleSelection"/>
                    </div>
                </div>
</template>
