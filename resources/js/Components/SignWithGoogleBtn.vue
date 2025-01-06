<script setup>
import {defineAsyncComponent} from "vue";
const GoogleG = defineAsyncComponent(() => import("@/Components/svg/icons/google-G.vue"))
</script>

<template>
    <v-btn :href="route('auth.google')" class="w-full bg-transparent border border-white/10 p-3 text-[14px] font-bold rounded-[12px]"
           min-height="44px" style="border-radius: 12px">
        <span class="flex items-center gap-2 leading-[13px] fm-bold"><GoogleG/> <slot /></span>
    </v-btn>
</template>
