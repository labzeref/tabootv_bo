<script setup>
import {onMounted, reactive, ref} from 'vue';
import Comment from "@/Components/Video/LongVideoSingleComment.vue";
import Send from "@/Components/svg/icons/send.vue";
import {usePage} from "@inertiajs/vue3";
import axios from "axios";
import SingleComment from "@/Components/Video/SingleComment.vue";

const user = usePage().props.auth.user;


const props = defineProps({
    video: Object,
})

const manager = reactive({
    commentList: [],
    showAllComments: true,
    toggleShowAllComments() {
        this.showAllComments = !this.showAllComments
    },
    getCommentList() {
        if (this.showAllComments) {
            return this.commentList;
        } else {
            return this.commentList.slice(0, 1);
        }
    },
    postComment(data, callable) {
        axios.post(route('videos.post-comment', props.video.uuid), data).then(response => {
            this.appendComment(response.data.comment);

            if (callable) {
                callable();
            }
        }).catch(error => {
            console.log('error', error)
        });
    },
    updateCommentList(list) {
        this.commentList = list;
    },
    appendComment(comment) {
        if (comment.parent_id) {
            const parentIndex = this.commentList.findIndex(c => c.id === comment.parent_id);
            if (this.commentList[parentIndex].replies) {
                this.commentList[parentIndex].replies.push(comment);
            } else {
                this.commentList[parentIndex].replies = [comment];
            }
        } else {
            this.commentList.unshift(comment);
        }
    }
})

const content = ref('');

const postComment = () => {
    manager.postComment({
        content: content.value,
    }, () => {
        content.value = '';
    });
}

const handleEnter = (event) => {
    if (event.shiftKey) {
        return;
    }

    event.preventDefault();
    postComment();
};

onMounted(() => {

    manager.updateCommentList(props.video.comments);

    if (window.innerWidth < 1024) {
        manager.showAllComments = false;
    }
})
</script>

<template>
    <p class="fs-20 fm-medium fw-700 leading-[150%]">{{ video.comments_count }} comments</p>
    <div class="flex items-end md:items-start gap-[10px] lg:gap-[21px] mt--text mb-[12px] md:my-[35px]">
        <v-img :src="user.dp" cover class="comment-profile"></v-img>
        <div class="flex items-end w-full ">
            <!-- <v-textarea label="Add Comment" row-height="48" rows="1"
                        auto-grow
                        shaped
                        color="white"
                        @keydown.enter="handleEnter"
                        class="comment-textarea fm-book"
                        v-model="content"

            ></v-textarea> -->
            <v-textarea
            v-model="content"
            label="Add Comment"

            rows="1"
            auto-grow
            shaped
            color="white"
            @keydown.enter="handleEnter"
            @focus="isFocused = true"
            @blur="isFocused = false"
            class="comment-textarea fm-book"
          >
            <template v-slot:label>
              <span v-show="!isFocused">Add Comment</span>
            </template>
          </v-textarea>
            <v-btn @click="postComment" :icon="Send" color="transparent" style="box-shadow: none"/>
        </div>
    </div>

    <div class="space-y-[4px] md:space-y-[35px]">
        <SingleComment v-for="(comment) in manager.getCommentList()" :manager :key="comment.uuid" :comment/>
    </div>

    <v-btn v-if="video.comments_count > 1" @click="manager.toggleShowAllComments()" color="transparent" style="box-shadow: none; padding-left: 0px; justify-content: left;" class="allComments"
           rounded="pill" height="20px" width="150px">
        {{ manager.showAllComments ? 'Hide comments' : 'See all comments' }}
    </v-btn>
</template>

<style scoped>
.allComments {
    color: #69C9D0 !important;
}

.label-hidden .v-label {
    display: none !important;
  }

@media (min-width: 1024px) {
    .allComments {
        display: none;
    }
}
</style>
