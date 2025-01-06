<script setup>
import {Link} from "@inertiajs/vue3"
import {defineAsyncComponent, onMounted, onUnmounted, ref} from "vue";
import FooterLogo from "@/Components/logo/FooterLogo.vue";

const Page = defineAsyncComponent(() => import('@/Components/Page.vue'));
const Facebook = defineAsyncComponent(() => import('@/Components/svg/icons/facebook.vue'));
const Instagram = defineAsyncComponent(() => import('@/Components/svg/icons/instagram.vue'));
const Tiktok = defineAsyncComponent(() => import('@/Components/svg/icons/tiktok.vue'));
const AppStore = defineAsyncComponent(() => import('@/Components/svg/icons/appStore.vue'));
const PlayStore = defineAsyncComponent(() => import('@/Components/svg/icons/playStore.vue'));
const RedRightArrow = defineAsyncComponent(() => import('@/Components/svg/icons/redRightArrow.vue'));

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const links = [
    {
        label: 'Customer support',
        to: 'https://taboo.tv/contact-us',
    },
    {
        label: 'Terms and conditions',
        to: 'https://taboo.tv/terms-&-conditions',
    },
    {
        label: 'Privacy policy',
        to: 'https://taboo.tv/privacy-policy',
    },
    {
        label: 'Refund policy',
        to: 'https://taboo.tv/refund-policy',
    },
]

const showScrollToTop = ref(false);

const checkScroll = () => {
    const screenHeight = window.innerHeight;
    const pageHeight = document.documentElement.scrollHeight;

    if (pageHeight > screenHeight * 1.2) {
        showScrollToTop.value = window.scrollY > screenHeight * 1.1;
    } else {
        showScrollToTop.value = false;
    }
};

onMounted(() => {
    window.addEventListener('scroll', checkScroll);
    checkScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', checkScroll);
});
</script>

<template>
    <div class="relative">
        <div class="absolute bottom-arrow radialBackground py-6">
            <v-btn v-if="showScrollToTop"
                   icon="" @click="scrollToTop()" class="bg-[#222] p-4 rounded-lg bottom-arrow-btn"
                   style="border: 1px solid rgba(255,255,255,10%)">
                <RedRightArrow class="scale-[200%] -rotate-90"/>
            </v-btn>
        </div>
    </div>
    <!--            class="py-[20px] md:py-[68px] px-[20px] md:px-[69px] grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-12 gap-8 md:gap-y-12 bg-[#0A070B] lg:max-h-[374px]">-->

    <div class="relative bg-[#0A070B] z-[5]">
        <Page
            class="py-[20px] md:py-[68px] px-[20px] md:px-[69px] flex flex-col lg:flex-row gap-12 bg-[#0A070B] footer-container">
            <div class="mr-auto flex flex-col items-start gap-y-8 lg:gap-y-[88px] first-child w-full max-w-[533px]">
                <footer-logo/>

                <div class="w-full max-w-[257px] space-y-3">
                    <p class="text-[20px] leading-[26px] text-[#514F51] font-normal">Address</p>
                    <p class="text-[20px] leading-[26px] text-[#6D6A6E] font-normal">Georgia, United States.</p>
                </div>
            </div>

            <div class="flex max-w-[800px] w-full justify-between">
                <!--            LINKS-->
                <div class="gap-y-4 grid w-full max-w-[209px] min-w-fit">
                    <a v-for="(link, key) in links" :key :href="link.to" v-text="link.label" target="_blank"
                       class="fm-book text-[16px] md:text-[20px] text-white leading-[26px] font-normal"></a>
                </div>

                <!--            DOWNLOADS-->
                <div class="space-y-4">
                    <p class="fs-20 fm-book leading-[26px] font-normal">Mobile Apps <span class="capitalize text-[14px] leading-[26px] font-normal">(Coming Soon)</span></p>
                    <app-store/>
                    <play-store/>
                </div>
            </div>

            <!--            FOLLOW US-->
            <!--            <div class="space-y-4 mr-auto md:mx-auto w-full max-w-[122px] min-w-fit">
                            <p class="fs-20 fm-book leading-[26px] font-normal">Follow us on</p>
                            <a href="https://www.facebook.com/ArabUncut/" target="_blank" class="leading-[16px] text-[14px] text-white flex items-center fm-book gap-2">
                                <facebook/>
                                Facebook
                            </a>
                            <a href="https://www.instagram.com/arab/?hl=en" target="_blank" class="leading-[16px] text-[14px] text-white flex items-center fm-book gap-2">
                                <Instagram/>
                                Instagram
                            </a>
                            <a href="https://www.tiktok.com/@arabuncuts" target="_blank" class="leading-[16px] text-[14px] text-white flex items-center fm-book gap-2">
                                <tiktok/>
                                Tiktok
                            </a>
                        </div>-->
        </Page>
        <hr class="w-full border mb-[41px]">

        <div class="w-full flex justify-center mb-[25px]">
                <span class="copyRight fm-book">
                    ©2024 Taboo Studios LLC
                </span>
        </div>
    </div>
</template>

<style scoped>


@media (min-height: 1424px) {
    .footer-container {
        max-height: 374px;
    }
}

.copyRight {
    font-size: 16px;
    font-weight: 400;
    line-height: 21.12px;
    text-align: center;
}

h5 {
    text-align: start;
}

</style>
