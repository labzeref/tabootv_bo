<script setup>
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    link: {
        type: Object,
    }
})

</script>

<template>
    <Link :href="link.url"
          :class=" link.active ? 'text-white' : 'text-secondary'">
        <p class="lg:flex items-center fs-16 gap-2 hidden font-bold fm-bold leading-[20px]">
            <component :is="link.icon" :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
            {{ link.label }}
        </p>
    </Link>
</template>
