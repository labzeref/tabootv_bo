<script setup>
import ProfileImg from '@/images/series-profile.jpg'
import {onMounted, ref} from 'vue';
import Mute from "@/Components/svg/icons/mute.vue";
import AllGood from "@/Components/svg/icons/allGood.vue";
import Warning from "@/Components/svg/icons/warning.vue";
import Comment from "@/Pages/Player/Partials/Comment.vue";
import Send from "@/Components/svg/icons/send.vue";

const props = defineProps({
    video: Object,
})
const newComment = ref('')
const localComments = ref([]);
const isLiked = ref(false);
const isDisliked = ref(false);
const showAllComments = ref('hidden lg:inline');
onMounted(() => {
    axios.get(route('video.comments',props.video.id)).then(response => {
            localComments.value = response.data.comments.data
    }).catch(error => {
        console.log('error',error.response)
    })
})

const toggleAllCommentsVisibility = () => {
    if (showAllComments.value === 'hidden lg:inline') {
        showAllComments.value = 'inline';
    } else {
        showAllComments.value = 'hidden lg:inline'
    }
}

function toggleLike() {
    isLiked.value = !isLiked.value;
    if (isLiked.value) {
        isDisliked.value = false;
    }
}

function toggleDislike() {
    isDisliked.value = !isDisliked.value;
    if (isDisliked.value) {
        isLiked.value = false;
    }
}

const postComment = () => {
    if (newComment.value.trim()) {
        axios.post(route('video.comment', props.videoId), {
            content: newComment.value
        }).then(response => {
            localComments.value = [...[response.data.comment], ...localComments.value]
            newComment.value = ''
        }).catch(error => {
        });
    }
}


const handleEnter = (event) => {
  if (event.shiftKey) {
    return;
  }
  event.preventDefault();
  postComment();
};



</script>

<template>
    <p class="fs-20 fm-medium fw-700">120 comments</p>
    <div class="flex items-start gap-[21px] mt-[4px] md:mt-[35px]">
        <v-img :src="ProfileImg" cover class="comment-profile"></v-img>
        <div class="flex items-end w-full">
            <v-textarea label="Add Comment" row-height="48" rows="1"
                        auto-grow
                        shaped
                        color="white"
                        @keydown.enter="handleEnter"
                        class="comment-textarea"
                        v-model="newComment"

            ></v-textarea>
            <v-btn @click="postComment" :icon="Send" color="transparent" style="box-shadow: none"/>
        </div>
    </div>

    <comment v-for="(comment, index) in localComments" :key="comment.id" :index="index" :all-comments="showAllComments"
             :comment/>

    <v-btn @click="toggleAllCommentsVisibility" color="transparent" style="box-shadow: none" class="allComments"
           rounded="pill" height="20px" width="150px">
        {{ showAllComments === 'inline' ? 'Hide comments' : 'See all comments' }}
    </v-btn>
</template>

<style scoped>
.allComments {
    color: #69C9D0 !important;
}

@media (min-width: 1024px) {
    .allComments {
        display: none;
    }
}
</style>
