<script setup>
import {defineAsyncComponent, onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";
import LiveChat from "@/Pages/LiveChat/LiveChat.vue";

const AppLayout = defineAsyncComponent(() => import("@/Layouts/AppLayout.vue"));
const Footer = defineAsyncComponent(() => import("@/Layouts/Footer.vue"));
const Navbar = defineAsyncComponent(() => import("@/Layouts/Navbar.vue"));

const navKey = ref(1);
onMounted(() => {
    router.on('finish', () => {
        navKey.value++;
    })
})

const isLiveChatOpen = ref(false);
</script>

<template>
    <AppLayout>
        <v-app-bar class="bg-dark py-[4px] lg:py-[16px] " style="  z-index: 100" :elevation="0">
            <Navbar :key="navKey"/>
        </v-app-bar>
        <LiveChat/>

        <div class="mt-[64px] md:mt-[96px]  radialBackground" :class="route().current('shorts.*') ? 'custom-h-screen' : 'padding-bottom'">
            <slot/>
        </div>
        <Footer v-if="!route().current('shorts.*')"/>
    </AppLayout>
</template>
<style scoped>
.radialBackground {
    background: radial-gradient(ellipse 50% 600px at 50% calc(100% + 110px), rgba(171, 0, 19, 0.71) 0%, rgba(16, 16, 16, 0) 100%);
}

.padding-bottom{
    padding-bottom: 120px;
}
.custom-h-screen{
    min-height: calc(100vh - 100px);
    padding-bottom: 0px !important;
}

@media (max-width: 768px) {
    .radialBackground {
        background: radial-gradient(ellipse 70% 200px at 50% 100%, #AB001395 0%, #0000 90%);
        padding-bottom: 100px;
    }
}

</style>
