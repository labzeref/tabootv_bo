<script setup>
import Cross from "@/Components/svg/icons/cross.vue";
import Send from "@/Components/svg/icons/send.vue";
import SingleComment from "@/Components/Video/SingleComment.vue";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const user = usePage().props.auth.user;

const props = defineProps({
    video: Object,
    manager: Object
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
        <div
             class="commentContainerpadding hidden md:inline absolute z-10 w-full max-w-[525px]   bg-[#2a2727] right-0 bottom-0 top-2">
            <div class="w-full sticky z-10 top-0 bg-[#2a2727]">

                <!--                Header-->
                <div class="bg-[#6d131d] px-6 py-5 flex items-center justify-between max-h-[60px]">
                    <p class="heading">{{video.comments_count}} comments</p>
                    <v-btn @click="manager.toggleComments()" :icon="Cross" color="transparent" rounded="pill"
                           style="box-shadow: none"/>
                </div>

                <!--                comment input-->
                <div class="flex items-end px-6 py-3">
                    <img :src="user?.dp" alt="hero img" class="size-[48px] rounded-full mr-5">
                    <v-textarea
                        label="Add Comment"
                        row-height="48"
                        rows="1"
                        v-model="content"
                        auto-grow
                        shaped
                        color="white"
                        class="comment-textarea"
                        @keydown.enter="handleEnter"></v-textarea>
<!--
                    <v-btn @click="postComment()" :icon="Send" color="transparent" style="box-shadow: none" />
-->
                </div>
            </div>

            <!--            body-->
            <div class="overflow-auto max-h-[690px] h-full comments p-6 space-y-8">
                <SingleComment v-for="(comment) in manager.commentList" :key="comment.uuid" :manager :comment="comment"/>
            </div>
        </div>
</template>

<style scoped>
.commentContainerpadding {
    border-radius: 30px 0px 0 30px !important;
    overflow-y:hidden
}

.heading {
    font-size: 19px;
    font-weight: 700
}

</style>
