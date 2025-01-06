<script setup>
import Tick from "@/Components/svg/icons/tick.vue";
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import Mute from "@/Components/svg/icons/mute.vue";
import AllGood from "@/Components/svg/icons/allGood.vue";
import Warning from "@/Components/svg/icons/warning.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    video: Object,
    totalCount: Number,
    currentVideoUuid: String,
    channel: Object,
    actionButtons: {
        type: Boolean,
        default: false
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

     <div class="relative" >
        <!-- <div class="absolute z-10 right-[19px] top-[10px]">
            <v-menu v-if="actionButtons">
                <template v-slot:activator="{ props }">
                    <v-btn :icon="true" class="min-h-[46px] p-0 flex items-center shadow-none menu"
                           color="transparent"
                           dark
                           v-bind="props"
                           elevation="0"
                    ><three-dots color="white" />
                    </v-btn>
                </template>

                <v-list class="px-2 border min-w-[170px] rounded-lg mt-1">
                    <v-list-item
                        v-for="(link, key) in actionMenu" :key
                        rounded="lg"
                        :href="link.url"
                        base-color="secondary"
                        density="compact"
                    >
                                <span class="flex gap-2 items-center">
                                    <component :is="link.icon"
                                               :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                                {{ link.label }}</span>
                    </v-list-item>
                </v-list>
            </v-menu>
        </div> -->

        <Link :href="route('videos.play',video?.uuid)" class="relative" :class="['trailor-bg']">

            <v-img cover :src="video?.thumbnail" class="trailor-cover-img"></v-img>
            <div class="w-full py-2 pl-3 pr-[25px] ">
                <p class="fs-20 fw-400 w-full mb-1 truncated-heading-2   leading-[30px]">{{ video?.title }}</p>
                <p class="fs-14 fw-400 text-accent max-w-[120px] md:mb-4">{{ video?.humans_publish_at }}</p>
                <div class="flex items-center gap-1">
                    <p class="fs-14 fw-400 text-secondary max-w-[120px]">{{ channel?.name }}</p>
                    <span><Tick/></span>
                </div>
            </div>
        </Link>
    </div>
    
</template>

<style scoped>
.menu {
    position: absolute;
    right: 2px;
    top: 2px;
    rotate: 90deg;
}

@media (max-width: 768px) {
    .menu {
        display: none;
    }
}
</style>
