<script setup>
import Tick from "@/Components/svg/icons/tick.vue";
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import Mute from "@/Components/svg/icons/mute.vue";
import AllGood from "@/Components/svg/icons/allGood.vue";
import Warning from "@/Components/svg/icons/warning.vue";
import {Link} from "@inertiajs/vue3";

defineProps({
    item: Object,
    user: Object,
orderNumber: Number,

totalCount: Number,
    playingVideoId: {
        type: Number,
        default: 0,
    },
    videoCount: {
        type: Number,
        default: 0,
    },
    random: {
        type: Boolean,
        default: false
    },
    customWidth: {
        type: String,
        default: 'max-width'
    },
    itemsCenter: {
        type: String,
        default: 'align-center'
    },
    widthAuto: {
        type: String,
        default: 'min-w-fit'
    },
    CustomMb: {
        type: String,
        default: 'mb-episode'
    },
    titleMb: {
        type: String,
        default: 'title-mb'
    }
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
    <Link :class="widthAuto" :href="random ? route('videos.play',item.uuid):route('series.video',item.uuid)">
        <div class="relative"
             :class="['trailor-bg', customWidth,  itemsCenter, { 'shadow-lg shadow-primaryDark': playingVideoId === item.id }]">
            <v-img cover :src="item.thumbnail" class="trailor-cover-img"></v-img>
            <div>
                <p class="fs-14 font-bold trailor-part mb-2 md:mb-4 hidden md:block"
                   :class="playingVideoId==item.id && 'bg-primaryDark'">Part {{ orderNumber+1 }}</p>
                <p class="fs-20 lh-30 font-medium w-full truncated-heading-2 break-all" :class="titleMb">{{ item.title }}</p>
                <p class="fs-14 fw-400 text-accent max-w-[120px]" :class="CustomMb">{{ item.humans_publish_at }}</p>
                <div class="min-w-fit items-center gap-1 hidden md:flex">
                    <p class="fs-14 fw-400 text-secondary max-w-[120px]">{{ item.channel?.name }}</p>
                    <span><Tick/></span>
                </div>

                <p class="fs-14 font-bold trailor-part mb-2 md:mb-4 md:hidden absolute bottom-3"
                   :class="playingVideoId==item.id && 'bg-primaryDark'">Part  {{ orderNumber+1 }}</p>

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
    .trailor-part{
        border: 1px solid #AB001385;
        box-shadow: 0px 4px 5px 0px #00000029;
        background: #AB00134F;

    }

    .menu {
        display: none;
    }
}
</style>
