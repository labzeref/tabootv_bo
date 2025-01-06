<script setup>
import {computed, defineAsyncComponent} from "vue";
import {formatDuration} from "../utils.js";
import {Link} from "@inertiajs/vue3";

const Tick = defineAsyncComponent(() => import('@/Components/svg/icons/tick.vue'));

const props = defineProps({
    item: Object,
})

const url = computed(() => {
    if (props.item.type === 'video') {
        return route('videos.play', props.item.uuid ?? 'notfound');
    } else {
        return route('series.trailer', props.item.id)
    }
})
</script>

<template>
    <div v-if="item.type === 'series'"
         class="absolute bg-[#1b1a1a] left-6 right-6 h-[100px] -top-0 rounded-[20px] border border-white z-5"></div>
    <div v-if="item.type === 'series'"
         class="absolute bg-[#282626] left-3 right-3 h-[100px] top-2 rounded-[20px] border border-white z-4"></div>

    <Link :href="url">

        <div :class="item.type === 'series' ? 'primaryMultiCard translate-y-5 z-20' : 'primaryCard'"
             class="min-h-[227px] home-card-padding flex flex-col hover:opacity-85 cursor-pointer duration-300 max-w-[213px] relative z-20 bg-dark">


            <div class="w-full relative overflow-hidden rounded-[15px] lg:min-h-[160px]">

                <img :src="item.thumbnail" alt="Video Image" class="w-full rounded-[6px]">

                <div :class="item.type === 'series' ? 'top-[30%]' : 'top-[50%]'"
                     class="absolute -inset-0 -bottom-1 bg-gradient-to-t from-dark to-dark/0"></div>
                <span v-if="item.latest"
                      class="absolute left-2 top-2 bg-white text-[11px] rounded px-[10px] py-[2px] font-bold">NEW</span>
            </div>


            <div class="w-full flex flex-col">
                <p v-if="item.type === 'series'"
                   class="series px-3 py-1 mb-[18px] mt-1 rounded-[20px] font-medium bg-white/15 w-fit text-[14px] hidden">
                    {{ item.videos_count }}
                    videos series</p>
                <h3 class="truncated-heading">{{ item.title }}</h3>
                <p class="text-sm text-[#9F9F9F] items-center gap-1 my-2 leading-[20px]" :class="'hidden md-flex'">
                    {{ item.channel?.name }}
                    <tick/>
                </p>
                <p class="publish_at text-sm text-secondary leading-[20px]">{{ item.humans_publish_at }}</p>

                <div class="display_name_video_count w-full flex justify-between items-center">
                    <div class="flex items-center gap-1">
                        <img :src="item.channel?.dp" alt="dp" class="rounded-full size-[18px]">
                        <p class="text-sm text-[#9F9F9F] flex items-center gap-1 leading-[20px]">
                            {{ item.channel?.name }}
                            <tick/>
                        </p>
                    </div>
                    <p v-if="item.type === 'series'"
                       class="series w-[65px] h-6 px-3 text-center rounded-[20px] font-bold bg-white/15 text-[8px] leading-[20px]">
                        {{ item.videos_count }}
                        videos</p>
                </div>
            </div>
        </div>
    </Link>


</template>

<style>
@media (max-width: 768px) {
    .primaryCard, .primaryMultiCard {
        row-gap: 10px;
    }
}
@media (min-width: 768px) {

    .truncated-heading {
        line-height: 22px !important;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1; /* Limit to 1 line */
        overflow: hidden;
        text-overflow: ellipsis; /* Adds ellipsis at the end */
        white-space: normal; /* Allows text to wrap */
    }

    .md-flex {
        display: flex;
    }

    .primaryMultiCard {
    }

    .primaryCard {
        padding-bottom: 20px;
    }

    .primaryCard, .primaryMultiCard {
        min-width: fit-content;

        row-gap: 19px;
        /*md:gap-[19px]*/
        max-width: 458px;
    }

    .series {
        font-size: 16px;
        display: inline;
    }

    .display_name_video_count {
        display: none;
    }
}


@media (max-width: 768px) {

    .publish_at {
        padding-bottom: 6px !important;
    }

    .truncated-heading {
        line-height: 18px !important;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2; /* Limit to 1 line */
        overflow: hidden;
        text-overflow: ellipsis; /* Adds ellipsis at the end */
        white-space: normal; /* Allows text to wrap */
    }

    .md-flex {
        display: none;
    }

    .series {
        order: 999;
        border: 1px solid var(--primary-dark-color);
        background-color: #AB00134F;
    }
}

.primaryMultiCard > div > img {
    aspect-ratio: 5 / 3;
    object-fit: cover
}

.primaryCard > div > img {
    aspect-ratio: 5 / 3;
    object-fit: cover
}

</style>
