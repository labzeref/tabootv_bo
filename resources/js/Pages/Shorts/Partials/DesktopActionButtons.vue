<script setup>
import Fire from "@/Components/svg/icons/fire.vue";
import CommentSvg from "@/Components/svg/icons/comment.vue";

defineProps({
    manager: Object
})
</script>

<template>
    <div class="md:flex flex-col gap-4 hidden">
        <v-btn :icon="Fire" height="58px" width="58px" :color="manager.hasLiked ? 'var(--primary-dark-color)' : ''"
               rounded="pill" @click="manager.toggleLike()"/>
        <v-btn @click="manager.toggleComments()" :icon="CommentSvg" height="58px" width="58px" rounded="pill"/>
    </div>
</template>
