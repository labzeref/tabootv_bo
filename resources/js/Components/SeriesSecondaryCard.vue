<script setup>
import {defineAsyncComponent, onMounted, computed} from "vue";
import {Link} from "@inertiajs/vue3";
import {formatDuration} from "@/utils.js";

const Tick = defineAsyncComponent(() => import('@/Components/svg/icons/tick.vue'));


const props = defineProps({
    object: Object,

    heightCustom: {
        type: String,
        default: 'h-auto'
    },
    customMargin: {
        type: String,
        default: 'margin-bottom'
    }
})

const thumbnail = computed(() => {
    return props.object.type === 'video' ? props.object.card_thumbnail : props.object.trailer_thumbnail;
})

const url = computed(() => {
    if (props.object.type === 'video') {
        return route('videos.play', props.object.uuid);
    } else {
        return route('series.trailer', props.object.id)
    }
})
</script>

<template>

    <template v-if="object.type === 'series'">
        <div
            class="absolute bg-[#1b1a1a] left-6 right-6 h-[70%] -top-0 rounded-[20px] border border-white z-5 hidden md:inline"></div>
        <div
            class="absolute bg-[#282626] left-3 right-3 h-[70%] top-2 rounded-[20px] border border-white z-4 hidden md:inline"></div>
    </template>
    <Link :href="url">
        <div :class="object.type === 'series' ? 'primaryMultiCard translate-y-5 z-20' : 'primaryCard'"
             class=" home-card-padding flex md:flex-col gap-[19px] hover:opacity-85 cursor-pointer duration-300 md:max-w-[458px] relative z-20 bg-dark">
            <div class="w-full relative overflow-hidden rounded-[15px]">
                <img :src="thumbnail" alt="placeholder Image" class="w-full rounded-[6px]" :class="heightCustom">
                <div :class="object.type === 'series'  ? 'top-[50%] md:top-[50%]' : 'top-[50%]'"
                     class="absolute -inset-0 -bottom-1 bg-gradient-to-t from-dark to-dark/0"></div>
                <span v-if="object.latest"
                      class="absolute left-[11px] top-[9px] bg-white fs-14 fm-bold rounded px-[10px] py-[2px] text-black-0b fw-700">NEW</span>
            </div>

            <div class="w-full flex flex-col md:justify-around">
                <p class="fs-card fm-medium font-medium truncated-heading md:mb-2">{{ object.title }}</p>
                <p class="fs-14 text-secondary-9f flex items-center gap-1 md:mb-2">{{ object.channel?.name }}
                    <tick/>
                </p>
                <p class="fs-14 text-secondary-9f " :class="customMargin">{{
                    new Date(object.published_at).toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "long",
                        day: "numeric"
                    })
                }}</p>
                <p v-if="object.type == 'series'"
                   class="series px-3 py-1 mb-[1px] rounded-[20px]  md:bg-white/15 w-fit fs-16 fm-medium">
                    {{ object.videos_count }}
                    videos series</p>
            </div>
        </div>
    </Link>
</template>

<style scoped>

@media (max-width: 768px) {
    .series {
        order: 999;
        border: 1px solid var(--primary-dark-color);
        background-color: rgba(255, 255, 255, 0.12);
    }

    .primaryMultiCard {
        background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 142.56%);
    }


    .primaryCard {
        background: linear-gradient(90deg, rgba(110, 2, 6, 0.77) 0%, rgba(55, 55, 55, 0.2) 142.56%);
    }
}

@media (min-width: 768px) {
    .primaryMultiCard > div > img {
        aspect-ratio: 5 / 3;
        object-fit: cover;
    }
}
</style>
