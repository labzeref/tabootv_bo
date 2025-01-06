<script setup>
import Tick from "@/Components/svg/icons/tick.vue";
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import Mute from "@/Components/svg/icons/mute.vue";
import AllGood from "@/Components/svg/icons/allGood.vue";
import Warning from "@/Components/svg/icons/warning.vue";
import {Link} from "@inertiajs/vue3";

defineProps({
    video: Object,
    orderNumber: Number,
    currentVideoUuid: String,
    channel: Object,
})


const actionMenu = [
    {
        icon: AllGood,
        label: 'Save',
        url: '#'
    },
    {
        icon: Warning,
        label: 'Report Video',
        url: '#'
    },
]

</script>

<template>
    <Link :href="route('series.video',video.uuid)">
        <div class="relative"
             :class="['trailor-bg', { 'shadow-lg shadow-primaryDark': video.uuid === currentVideoUuid }]">
            <v-img :src="video?.thumbnail" class="trailor-cover-img"></v-img>
            <div>
                <p class="fs-14 font-bold trailor-part mb-2 md:mb-4 hidden md:block"
                   :class="currentVideoUuid === video?.uuid && 'bg-primaryDark'">Part {{ orderNumber+1 }}</p>
                <p class="fs-20 fw-400 w-full mb-1 truncated-heading-2">{{ video.title }}</p>
                <p class="fs-14 fw-400 text-accent max-w-[120px] mb-2 md:mb-4">{{ video?.humans_publish_at }}</p>
                <div class="items-center gap-1 hidden md:flex">
                    <p class="fs-14 fw-400 text-secondary max-w-[120px]">{{ channel?.name }}</p>
                    <span><Tick/></span>
                </div>

                <p class="fs-14 font-bold trailor-part mb-2 md:mb-4 md:hidden absolute bottom-3"
                   :class="currentVideoUuid === video?.uuid && 'bg-primaryDark'">Part {{ orderNumber+1 }}</p>

            </div>
        </div>
    </Link>
</template>

<style scoped>
.menu {
    position: absolute;
    right: 2px;
    top: 2px;
    rotate: 90deg;
}

@media (max-width: 768px) {
    .trailor-part {
        border: 1px solid #AB001385;
        box-shadow: 0px 4px 5px 0px #00000029;
        background: #AB00134F;
    }

    .menu {
        display: none;
    }
}
</style>
