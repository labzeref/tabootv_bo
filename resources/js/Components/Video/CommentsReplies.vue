<script setup>
import commentsArrow from '@/Components/svg/icons/commentsArrow.vue';
import Like from '@/Components/svg/icons/like.vue';
import Dislike from '@/Components/svg/icons/dislike.vue';
import ThreeDots from '@/Components/svg/icons/threeDots.vue';

const props = defineProps({
    reply: Object,
    parentId: Number,
})

function toggleLike() {
}

function toggleDislike() {
}
</script>


<template>
    <transition name="fade" mode="out-in">
      <div class="flex items-start gap-2.5 mt-[25px] pl-8">
        <commentsArrow />
        <div class="w-full">
          <div class="flex justify-between align-center w-full gap-2 ">
            <div class="flex items-center gap-2 md:gap-3">
              <img :src="reply.user?.dp" alt="Channel Image" cover class="comment-profile" />
              <p class="fs-18 fw-700 fm-bold capitalize">{{ reply.user?.display_name }}</p>
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
            {{reply.message}}
          </p>
        </div>
      </div>
    </transition>
  </template>

