<script setup>
import like from '@/Components/svg/icons/like.vue'
import dislike from '@/Components/svg/icons/dislike.vue'
import {ref} from 'vue';
import Warning from "@/Components/svg/icons/warning.vue";
import replySvg from "@/Components/svg/icons/reply.vue";
import Hide from "@/Components/svg/icons/hide.vue";
import Send from "@/Components/svg/icons/send.vue";
import ProfileImg from '@/assets/profile-img.png';
import CommentsReplies from "@/Components/Video/CommentsReplies.vue";


const props = defineProps({
    index: Number,
    comment: Object,
    allComments: String
})


const isLiked = ref(props.comment.has_liked);
const isDisliked = ref(props.comment.has_disliked);
const showReplyInput = ref(false);
const showReplies = ref(false);
const commentReplies = ref([]);
const tempCommentReplies = ref([]);
const newReplyText = ref("");
const toggleReply = () => {
    showReplyInput.value = !showReplyInput.value
}

function toggleLike() {

    axios.post(route('postComments.like',props.comment.id))
        .then(response => {
            isLiked.value = !isLiked.value;
            if (isLiked.value) {
                isDisliked.value = false;
            }
        })
        .catch(error => {

        })

}
function toggleDislike() {
    axios.post(route('postComments.dislike',props.comment.id))
        .then(response => {
            isDisliked.value = !isDisliked.value;
            if (isDisliked.value) {
                isLiked.value = false;
            }
        })
        .catch(error => {
        })
}

const GetAllReplies = () => {
    if (!showReplies.value){
        tempCommentReplies.value = [];
        axios.get(route('postComments.replies',props.comment.id))
            .then(response => {
                commentReplies.value = response.data.postComment.data;
                showReplies.value = true;
            })
            .catch(error => {
                console.log(error);
            })
    }else{
        showReplies.value = false;
    }
}

const submitReply = () => {
    if (!newReplyText.value.trim())
    {
        alert("Comment cannot be empty or just spaces.");
        return;
    }

    axios.post(route('postComments.store',props.comment.post_id),{
        parent_id: props.comment.id,
        content: newReplyText.value,
    }).then(response => {
        let myComment = response.data.postComment
        myComment.created_at = 'just now'
        if  (showReplies.value){
            commentReplies.value.push(myComment)
            tempCommentReplies.value = [];
        }else{
            tempCommentReplies.value.push(myComment)

        }
        newReplyText.value = "";
        props.comment.replies_count++
    }).catch(error => {
        console.error("Error posting reply:", error);
    })
}


const actionMenu = [
    {
        icon: replySvg,
        label: 'Reply',
        url: '#',
    },
    {
        icon: Hide,
        label: 'Hide',
        url: '#'
    },
    {
        icon: Warning,
        label: 'Report',
        url: '#'
    },
]
</script>

<template>
    <div :class="index > 0 ? allComments : ''">
        <div class="flex justify-between align-center mt-5 md:mt-[35px]">
            <div class="flex items-center gap-2 md:gap-3 ">
                <img :src="comment.user?.dp" alt="Channel Image" cover class="comment-profile object-cover">
                <p class="fs-18 fw-700 fm-bold capitalize">{{comment.user?.display_name}}</p>
                <p class="fs-16 fw-400 fm-book text-secondary">{{comment.created_at}}</p>
            </div>
            <div class="flex items-center gap-4 md:gap-8">
                <div class="cursor-pointer" @click="toggleLike">
                    <like :isActive="isLiked"/>
                </div>
                <div class="cursor-pointer" @click="toggleDislike">
                    <dislike :isActive="isDisliked"/>
                </div>
                <div class="cursor-pointer">
                    <v-btn :icon="replySvg"
                    @click="toggleReply()"
                        class="min-h-[46px] p-0 flex items-center shadow-none"
                        color="transparent"
                        dark
                        v-bind="props"
                        elevation="0"
                    >
                    </v-btn>
                </div>
            </div>
        </div>

        <p class="fs-16 fm-book fw-400 mt-4 mb-3 max-w-[90%]">{{comment.content}}</p>


        <v-scroll-y-transition v-show="showReplyInput">
            <div class="flex items-end">
                <v-textarea label="Reply.." row-height="48" rows="1"
                            auto-grow
                            shaped
                            color="white"
                            @keyup.enter="submitReply"
                            class="comment-textarea"
                            v-model="newReplyText"
                ></v-textarea>
                <v-btn :icon="Send" @click="submitReply" color="transparent" style="box-shadow: none"></v-btn>
            </div>
        </v-scroll-y-transition>
        <div v-if="comment.replies_count">
            <p class="replies cursor-pointer" @click="GetAllReplies">
                {{comment.replies_count}}  replies
            </p>
            <div v-show="showReplies">
                <CommentsReplies v-for="(reply, index) in commentReplies"
                :reply
                :index
                :key="reply.id"

                />
            </div>
            <div>
                <CommentsReplies v-for="(reply, index) in tempCommentReplies"
                                 :reply
                                 :index
                />
            </div>
        </div>

    </div>
</template>

<style>
.replies {
    color: #69C9D0;font-family: Gotham;
    font-size: 16px;
    font-weight: 500;
    line-height: 18px;
    text-align: left;
    text-underline-position: from-font;
    text-decoration-skip-ink: none;
}
</style>
