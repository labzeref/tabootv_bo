<script setup>
import {Link} from "@inertiajs/vue3"
import {defineAsyncComponent} from "vue";

const RedRightArrow = defineAsyncComponent(() => import('@/Components/svg/icons/redRightArrow.vue'));

defineProps({
    title: String,
    hasLink: {
        type: String,
        required: false,
        default: '#'
    }
})
</script>

<template>
    <div class="space-y-3 md:space-y-[25px]">
        <div class="flex justify-between items-center">
            <h2 class="capitalize h2-medium" v-text="title"></h2>
            <Link v-if="hasLink !== '#'" :href="hasLink" class="items-center gap-1 text-[16px] hidden md:flex w-[76px] max-h-[22px]">See all
                <red-right-arrow/>
            </Link>
        </div>
        <slot/>
    </div>
</template>
