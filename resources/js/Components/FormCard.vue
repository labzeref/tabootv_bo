<script setup>
defineProps({
    radialBackground: {
        type: Boolean,
        default: true
    }
})
</script>

<template>
    <div
        class="w-full max-w-[996px] py-[51px] px-[13px] bg-[#0A070B]/70 rounded-[20px] overflow-hidden flex justify-center relative">
        <div v-if="radialBackground" class="absolute inset-0 radialBackground"></div>
        <slot />
    </div>

</template>

<style scoped>
.radialBackground {
    background: radial-gradient(93.6% 93.6% at -27.96% 113%, rgba(171, 0, 19, 0.31) 0%, rgba(167, 0, 11, 0) 100%);
    pointer-events: none;
}

</style>
