<script setup>
import Cross from "@/Components/svg/icons/cross.vue";
import Send from "@/Components/svg/icons/send.vue";
import Comment from "@/Components/Video/SingleComment.vue";
import {ref} from "vue";

const props = defineProps({
    video: Object,
    manager: Object,
})


const content = ref('');
const handleEnter = (event) => {
    if (event.shiftKey) {
      return;
  }
    event.preventDefault();
    postComment();
};

const postComment = () => {
    props.manager.postComment({
        content: content.value
    }, () => {
        content.value = '';
    });
}
</script>

<template>
    <div v-if="manager.showComments" class="absolute z-40 inset-0 md:hidden" @click="manager.toggleComments()"></div>
    <v-slide-y-reverse-transition v-show="manager.showComments"
                                  class="z-50 absolute bottom-0 left-0 right-0 p-3 space-y-3 md:hidden">
        <div>
            <div class="rounded-[12px] bg-white/10 h-[438px] w-full py-2 pl-2 overflow-hidden"
                 style="backdrop-filter: blur(15px)">
                <div class="flex items-center justify-between sticky top-0">
                    <p class="font-bold text-sm">{{video?.comments_count}} comments</p>
                    <v-btn :icon="Cross" color="transparent" height="40px" width="40px"
                           @click="manager.toggleComments()"/>

                </div>
                <div class="h-full w-full space-y-5 overflow-y-auto overflow-x-hidden max-h-[380px]">
                    <Comment v-for="(comment, key) in manager.commentList" :key :manager :comment="comment"/>
                </div>

            </div>
            <div class="rounded-[12px] bg-white/10 w-full flex items-center gap-3 p-2 relative"
                 style="backdrop-filter: blur(15px)">
                <img src="@/images/series-profile.jpg" alt="hero img" class="size-[30px] rounded-full">
                <v-textarea label="Add Comment" row-height="8" rows="1" density="compact"
                            auto-grow
                            v-model="content"
                            shaped
                            color="white"
                            class="comment-textarea"
                            @keydown.enter="handleEnter"
                ></v-textarea>
                <v-btn :icon="Send" @click="postComment" color="transparent" height="30px" width="30px"
                       style="box-shadow: none; position: absolute; right: 5px"/>
            </div>
        </div>
    </v-slide-y-reverse-transition>
</template>

<style scoped>

/* For Webkit-based browsers like Chrome, Safari, and Edge */
::-webkit-scrollbar {
    width: 2px; /* width of the scrollbar */
}

::-webkit-scrollbar-track {
    background-color: black; /* track (background) color */
}

::-webkit-scrollbar-thumb {
    background-color: white; /* thumb (scroll handle) color */
    border-radius: 10px; /* round corners */
    border: 3px solid black; /* creates padding between thumb and track */
}

::-webkit-scrollbar-thumb:hover {
    background-color: white; /* thumb color on hover */
}

/* For Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: #FFFFFF1A transparent; /* thumb color and track color */
}
</style>
