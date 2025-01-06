<script setup>
import {onMounted, reactive} from "vue";
import axios from "axios";
import Header from "@/Pages/Shorts/Partials/Header.vue";
import DesktopCommentSection from "@/Pages/Shorts/Partials/DesktopCommentSection.vue";
import MobileCommentSection from "@/Pages/Shorts/Partials/MobileCommentSection.vue";
import ShortVideoPlayer from "@/Pages/Shorts/Partials/ShortVideoPlayer.vue";
import MobileActionButtons from "@/Pages/Shorts/Partials/MobileActionButtons.vue";
import DesktopActionButtons from "@/Pages/Shorts/Partials/DesktopActionButtons.vue";

const props = defineProps({
    video: Object,
    index: Number,
})

const manager = reactive({
    commentList: [],
    showComments: false,
    commentData: {
        content: '',
        parent_id: null,
    },
    hasLiked: props.video.has_liked,
    toggleComments() {
        this.showComments = !this.showComments
    },
    toggleLike() {
        axios.post(route('videos.toggle-like', props.video.uuid)).then(response => {
            this.hasLiked = !this.hasLiked;
        })
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

onMounted(() => {
    manager.updateCommentList(props.video.comments)
})
</script>

<template>
    <div class="relative w-full max-w-shorts flex flex-col overflow-hidden" style="aspect-ratio: 9/16;height: 100%;width: auto">
        <!--        Header-->
        <Header :video/>

        <!--        body-->
        <div class="relative mx-auto md:ml-auto md:mr-0 flex items-end gap-7">
            <!--            player-->
            <ShortVideoPlayer :url="video.url_720" :index/>

            <!--           Desktop action buttons-->
            <DesktopActionButtons :manager/>

            <!--            Mobile action buttons-->
                <MobileActionButtons :manager :dp="video?.channel?.dp"/>
        </div>
        <!--    Mobile Comment Section-->
        <MobileCommentSection :manager :video="video"/>
    </div>

    <!--        Desktop Comment Section -->
    <v-slide-x-reverse-transition v-show="manager.showComments">
        <DesktopCommentSection :manager :video="video"/>
    </v-slide-x-reverse-transition>
</template>

<style scoped>
.messageContainer-move,
.messageContainer-enter-active {
    transition: all 250ms ease;
}

.messageContainer-enter-from {
    opacity: 0;
    transform: translateY(-80%);
}

.commentContainer {
    border-radius: 30px 0 0 30px;
    overflow-y: scroll;
}

.heading {
    font-size: 19px;
    font-weight: 700
}


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
    scrollbar-color: white transparent; /* thumb color and track color */
}
</style>
