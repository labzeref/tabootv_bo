<script setup>
import {onMounted, ref, watch} from 'vue';
import Warning from "@/Components/svg/icons/warning.vue";
import replySvg from "@/Components/svg/icons/reply.vue";
import Hide from "@/Components/svg/icons/hide.vue";
import Send from "@/Components/svg/icons/send.vue";
import ProfileImg from '@/assets/profile-img.png';
import Comment from "@/Pages/Community/Partials/Comment.vue";


const props = defineProps({
    post: 'Object',
    index: Number,
    allComments: String,
    showReplySection: Boolean,
})


const isLiked = ref(false);
const isDisliked = ref(false);
const reply = ref(false);
const comments = ref([]);
const newComment = ref("");
const getComments = () => {
    axios.get(route('postComments.list',props.post.id))
        .then(response => {
            comments.value = response.data.postComment.data
        })
        .catch(error => {
            console.log(error)
        });
}

watch(
    () => props.showReplySection,
    (value) => {
        if (value){
            getComments();
        }else{
            comments.value = [];
        }
    }
)

const storeComment = () => {

    if (!newComment.value.trim()) {
        alert("Comment cannot be empty or just spaces.");
        return;
    }
    axios.post(route('postComments.store',props.post.id),{
        content : newComment.value
    })
        .then(response => {
            let myComment = response.data.postComment
            myComment.created_at = 'just now'
            comments.value.unshift(myComment)
            newComment.value = "";
            props.post.comments_count++;

        })
        .catch(error => {
            console.error("Error posting comment:", error);
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
    <p class="fs-20 fm-medium fw-700">{{ post.comments_count }} comments </p>
    <div class="flex items-start gap-[21px] mt-[4px] md:mt-[25px]">
        <v-img :src="ProfileImg" cover class="comment-profile"></v-img>
        <div class="flex items-end w-full">
            <v-textarea label="Add Comment" row-height="48" rows="1"
                        auto-grow
                        shaped
                        color="white"
                        @keydown.enter="storeComment"
                        class="comment-textarea"
                        v-model="newComment"

            ></v-textarea>
            <v-btn @click="storeComment" :icon="Send" color="transparent" style="box-shadow: none"/>
        </div>
    </div>

    <Comment
        v-for="(comment, index ) in comments"
        :key="comment.id"
        :comment
        :index
    />
</template>
