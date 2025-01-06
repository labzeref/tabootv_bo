<script setup>
import {defineAsyncComponent} from "vue";

const AppLayout = defineAsyncComponent(() => import("@/Layouts/AppLayout.vue"));
</script>

<template>
    <AppLayout>
        <div class="w-full min-h-screen Container">
            <div class="w-full backdrop-blur-2xl min-h-screen p-3 py-8 grid place-items-center">
                <slot/>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
.Container {
    background-image: url("@/images/backgrounds/authbg.png");
    background-size: cover;
    background-repeat: no-repeat;
    backdrop-filter: blur(40px);
}

@media (max-width: 768px) {
    .Container{
        background-image: url("@/images/backgrounds/authSmallBg.png");
    }
}
</style>
