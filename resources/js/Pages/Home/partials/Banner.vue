<script setup>
import {computed, defineAsyncComponent} from 'vue';
import {Link} from "@inertiajs/vue3";

const BtnSecondary = defineAsyncComponent(() => import('@/Components/BtnSecondary.vue'));
const Play = defineAsyncComponent(() => import('@/Components/svg/icons/Play.vue'));
const BannerInnerSlider = defineAsyncComponent(() => import('@/Pages/Home/partials/BannerInnerSlider.vue'));

const props = defineProps({
    banner: Object
})

const thumbnail = computed(() => {
    return props.banner.type === 'video' ? props.banner.thumbnail : props.banner.trailer_thumbnail;
})

</script>

<template>
    <!--    this gradient appears on mobile screens as background layer of title and button , DO NOT REMOVE !!-->
    <div class="bg-gradient-to-br from-[#FF5F2D]/35 to-[#FF5F2D]/5 banner-slide-height    rounded-[30px]">

        <div
            class="w-full relative rounded-[30px] bannerDiv lg:overflow-hidden image-slide-height"
            :style="{ backgroundImage: `url(${thumbnail})` }"
        >

            <!--            left-->
            <div
                class="top-banner-content h-full w-full max-w-[801px] flex flex-col justify-center items-start p-[18px] md:pt-[107px] md:pb-[89px] absolute inset-0 md:inset-0 md:relative">
                <div
                    class="max-w-[534px] max-h-[340px] relative z-2 space-y-[14px] md:space-y-[30px] backdrop-blur-md md:backdrop-blur-none flex flex-col items-center md:items-start mx-auto stickyCard ">
                    <div class="space-y-[4px] md:space-y-[6px]">
                        <h3 class="md:hidden" style="font-size: 15px !important; font-weight: 700 !important; line-height: 24px !important; text-align: center;">{{ banner.channel?.name }}</h3>
                        <h1 style="font-size: 31px; font-weight: 700" class="truncated-heading-2">{{ banner.title }}</h1>
                        <h3 class="hidden md:inline" style="font-weight: 700;">{{ banner.channel?.name }}</h3>
                        <p class="text-[9px] md:text-sm leading-[14px] truncated-heading-2">{{ banner.description }}</p>
                    </div>
                    <Link :href="banner.type ==='series'? route('series.trailer',banner.id) : route('videos.play',banner.uuid) ">
                        <BtnSecondary >
                            <Play/>
                            Play Now
                        </BtnSecondary>
                    </Link>
                </div>
                <div v-if="banner.videos?.length > 3" class="inner-slider relative mt--banner-content mx-auto">
                    <BannerInnerSlider :videos="banner.videos"/>
                </div>
            </div>

            <!--            right-->
        </div>
    </div>
</template>

<style scoped>
.bannerDiv {
    background-position: top;
    background-size: cover;
}

@media (max-width: 960px) {
    .inner-slider{
        display: none;
    }
}

@media (max-width: 767px) {


    .heroImage {
        overflow: hidden;
    }

    p {
        text-align: center;
    }

    h1, h3 {
        text-align: center;
    }
}
</style>
