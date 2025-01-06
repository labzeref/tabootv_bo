<script setup>
import ProfileImg from '@/images/series-profile.jpg'
import like from '@/Components/svg/icons/like.vue'
import dislike from '@/Components/svg/icons/dislike.vue'
import {onMounted, ref} from 'vue';
import ThreeDots from '@/Components/svg/icons/threeDots.vue';
import CommentsReplies from './CommentsReplies.vue';
import Warning from "@/Components/svg/icons/warning.vue";
import replySvg from "@/Components/svg/icons/reply.vue";
import Hide from "@/Components/svg/icons/hide.vue";
import Send from "@/Components/svg/icons/send.vue";

const props = defineProps({
    comment: Object,
    video: Object,
    index: Number,
    allComments: String
})

const localReplies = ref([])
const isLiked = ref(false);
const isDisliked = ref(false);
const showInput = ref(false);
const replyContent = ref('');
const hasAnyReply = ref(false);
const showReplies = ref(false)

onMounted(() => {
    localReplies.value = props.comment.replies
    hasAnyReply.value = props.comment.has_replies
})
const toggleReply = () => {
    showInput.value = !showInput.value
}

const toggleLike = () => {
    isLiked.value = !isLiked.value;
    if (isLiked.value) {
        isDisliked.value = false;
    }
}

const toggleDislike = () => {
    isDisliked.value = !isDisliked.value;
    if (isDisliked.value) {
        isLiked.value = false;
    }
}


const replyComment = (event) => {
    if (event.shiftKey) {
        return;
    }
    if (!replyContent.value.trim()) {
        return;
    }
    event.preventDefault();
    axios.post(route('videos.post-comment', props.video.uuid), {
        content: replyContent.value,
        comment_id: props.comment.uuid,
    }).then(response => {
        hasAnyReply.value = true;
        replyContent.value = '';
        localReplies.value.push(response.data.comment)
    }).catch(error => {
        console.log(error.response)
    })
};

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
                <img :src="comment.user?.dp" alt="Channel Image" cover class="comment-profile">
                <p class="fs-18 fw-700 fm-bold capitalize">{{ comment.user?.display_name }}</p>
                <p class="fs-16 fw-400 fm-book text-secondary">2 days ago</p>
            </div>
            <div class="flex items-center gap-4 md:gap-8">
                <div class="cursor-pointer" @click="toggleLike">
                    <like :isActive="isLiked"/>
                </div>
                <div class="cursor-pointer" @click="toggleDislike">
                    <dislike :isActive="isDisliked"/>
                </div>
                <div class="cursor-pointer">
                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <v-btn :icon="ThreeDots" class="min-h-[46px] p-0 flex items-center shadow-none"
                                   color="transparent"
                                   dark
                                   v-bind="props"
                                   elevation="0"
                            >
                            </v-btn>
                        </template>

                        <v-list class="px-2 border min-w-[170px] rounded-lg mt-1">
                            <v-list-item
                                v-for="(link, key) in actionMenu" :key
                                rounded="lg"
                                base-color="secondary"
                                density="compact"
                            >
                        <span class="flex gap-2 items-center" @click="link.label === 'Reply' ? toggleReply() : ''"
                        >
                            <component :is="link.icon"
                                       :color=" link.active ? '#FFFF' : '#9f9f9f'"/>
                        {{ link.label }}</span>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </div>
        </div>

        <p class="fs-16 fm-book fw-400 mt-4 mb-3">{{ comment.message }} </p>

        <v-scroll-y-transition v-show="showInput">
            <div class="flex items-end">
                <v-textarea label="Reply.." row-height="48" rows="1"
                            auto-grow
                            shaped
                            v-model="replyContent"
                            @keydown.enter="replyComment"
                            color="white"
                            class="comment-textarea"
                ></v-textarea>
                <v-btn :icon="Send" @click="replyComment" color="transparent" style="box-shadow: none"></v-btn>
            </div>
        </v-scroll-y-transition>
        <div v-show="hasAnyReply">
            <div class="fs-16 fw-500 text-purpledo fm-medium cursor-pointer my-2" @click="showReplies = !showReplies">{{ showReplies ? 'Hide replies' : 'Show replies'}}</div>
            <CommentsReplies v-if="showReplies" v-for="reply in localReplies" :key="reply.id" :parent-id="comment.id" :reply/>
        </div>
    </div>
</template>
