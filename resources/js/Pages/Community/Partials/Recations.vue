<script setup>
import fireImg from '@/assets/svg/fire.svg'
import basketImg from '@/assets/svg/waste-basket.svg'
import msgImg from '@/assets/svg/massenger.svg'
import {onMounted, ref} from "vue";

const props = defineProps({
    toggleReply: Function,
    post: Object,
})
const likeCounter = ref(props.post.likes_count);
const disLikeCounter = ref(props.post.dislikes_count);

const like = () => {
    axios.post(route('posts.like',props.post.id))
        .then(response => {
            likeCounter.value = response.data.likes_count
            disLikeCounter.value = response.data.dislikes_count

        })
        .catch(error=>{

        })
}

const dislike = () => {
    axios.post(route('posts.dislike',props.post.id))
        .then(response => {
            likeCounter.value = response.data.likes_count
            disLikeCounter.value = response.data.dislikes_count
        })
        .catch(error=>{

        })
}
</script>

<template>
    <div class="d-flex items-center gap-3">
        <div class="cursor-pointer d-flex items-center gap-2.5 pl-2" @click="like">
            <img :src="fireImg" alt="">
            <p class="fs-16 lh-16 text-secondary-9f">{{ likeCounter }}</p>
        </div>
        <div class=" cursor-pointer d-flex items-center gap-2.5 pl-2" @click="dislike">
            <img :src="basketImg" alt="">
            <p class="fs-16 lh-16 text-secondary-9f">{{ disLikeCounter }}</p>
        </div>
        <div class="cursor-pointer d-flex items-center gap-2.5 pl-2" @click="toggleReply">
            <img :src="msgImg" alt="">
            <p class="fs-16 lh-16 text-secondary-9f">{{ post.comments_count }}</p>
        </div>
    </div>
</template>
