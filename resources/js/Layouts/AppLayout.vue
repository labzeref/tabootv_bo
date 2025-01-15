<script setup>
import { computed } from 'vue';

const creatorsSegment = computed(() => {
  const segments = window.location.pathname.split('/'); // Split the path into segments
  return segments[1]; // Get the second segment (e.g., 'creators')
});
</script>

<template>
  <v-app
    class="bg-dark"
    :class="creatorsSegment === 'creators' || creatorsSegment === 'creator' ? 'h-[100dvh]' : null"
  >
    <slot />
  </v-app>
</template>
