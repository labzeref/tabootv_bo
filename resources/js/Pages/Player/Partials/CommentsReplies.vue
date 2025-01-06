<script setup>
import { ref } from 'vue';
import commentsArrow from '@/Components/svg/icons/commentsArrow.vue';
import ProfileImg from '@/images/series-profile.jpg'
import Like from '@/Components/svg/icons/like.vue';
import Dislike from '@/Components/svg/icons/dislike.vue';
import ThreeDots from '@/Components/svg/icons/threeDots.vue';

const props = defineProps({
    replay: Object,
    parentId: Number,
})

const visibleReply = ref(null);

function toggleReply(id) {
    if (visibleReply.value === id) {
        visibleReply.value = null;
    } else {
        visibleReply.value = id;
    }
}

function toggleLike() {
}

function toggleDislike() {
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>



<template>

    <div class="fs-16 fw-500 text-purpledo fm-medium cursor-pointer" @click="toggleReply(1)">Replies</div>

    <transition name="fade" mode="out-in">
      <div v-if="visibleReply === 1" class="flex items-start gap-2.5 mt-[25px] pl-8">
        <commentsArrow />

        <div>
          <div class="flex justify-between align-center w-full">
            <div class="flex items-center gap-2 md:gap-3">
              <img :src="replay.user?.dp" alt="Channel Image" cover class="comment-profile" />
              <p class="fs-18 fw-700 fm-bold capitalize">{{ replay.user?.display_name }}</p>
              <p class="fs-16 fw-400 fm-book text-secondary">2 days ago</p>
            </div>
            <div class="flex items-center gap-4 md:gap-8">
              <div class="cursor-pointer" @click="toggleLike">
                <Like :isActive="isLiked" />
              </div>
              <div class="cursor-pointer" @click="toggleDislike">
                <Dislike :isActive="isDisliked" />
              </div>
              <div class="cursor-pointer">
                <three-dots />
              </div>
            </div>
          </div>
          <p class="fs-16 fm-book fw-400 mt-4 mb-3">
            {{replay.message}}
          </p>
        </div>
      </div>
    </transition>
  </template>

