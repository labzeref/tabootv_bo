<script setup>
import CommentReply from "@/Pages/Shorts/Partials/commentReply.vue";
import {onMounted, reactive, ref} from "vue";
import ThreeDots from "@/Components/svg/icons/threeDots.vue";
import Like from "@/Components/svg/icons/like.vue";
import Dislike from "@/Components/svg/icons/dislike.vue";
import replySvg from "@/Components/svg/icons/reply.vue";
import Hide from "@/Components/svg/icons/hide.vue";
import Warning from "@/Components/svg/icons/warning.vue";
import Send from "@/Components/svg/icons/send.vue";
import axios from "axios";
import CommentsArrow from "@/Components/svg/icons/commentsArrow.vue";

const props = defineProps({
    comment: Object,
    manager: Object,
    isReply: {
        type: Boolean,
        default: false
    }
})

const content = ref('');
const showReplies = ref(false);
const showReplyInput = ref(false);

const postComment = () => {
    props.manager.postComment({
        content: content.value,
        parent_id: props.comment.parent_id ?? props.comment.id,
    }, () => {
        content.value = '';
        showReplies.value = true;
        showReplyInput.value = false;
    })
}
const handleEnter = (event) => {
    if (event.shiftKey) {
      return;
  }
    event.preventDefault();
    postComment();
};

const liked = ref(false);
const disliked = ref(false);

const toggleLike = () => {
    axios.post(route('comments.toggle-like', props.comment.uuid)).then(response => {
        if (disliked.value) {
            disliked.value = false
        }

        liked.value = !liked.value;
    })
}
const toggleDislike = () => {
    axios.post(route('comments.toggle-dislike', props.comment.uuid)).then(response => {
        if (liked.value) {
            liked.value = false
        }
        disliked.value = !disliked.value;
    });
}

onMounted(() => {
    liked.value = props.comment.has_liked;
    disliked.value = props.comment.has_disliked;
})
</script>

<template>
    <div class="w-full ">
        <div class="flex items-center justify-between gap-1">
            <div class="flex items-center gap-2 min-w-fit">
                <img :src="comment?.user?.dp" alt="hero img" class="size-[30px] md:size-[48px] rounded-full">
                <div class="d-flex align-items-center">
                    <p class="heading leading-[18px]">{{ comment?.user?.display_name }}</p>
                    <img v-if="comment?.user?.badge" :src="comment?.user?.badge" alt="placeholder Image" class="ms-1 my-auto" style="height: 15px;width: auto">
                </div>
                <span class="fs-16 text-[#807E81] fm-book">{{ comment?.created_at }}</span>
            </div>

            <div class="flex items-center justify-between w-full max-w-147px gap-3">
                <div class="cursor-pointer" @click="toggleLike">
                    <like :isActive="liked"/>
                </div>
                <div class="cursor-pointer" @click="toggleDislike">
                    <dislike :isActive="disliked"/>
                </div>
                <v-btn :icon="true" class="min-h-[46px] p-0 flex items-center shadow-none"
                    color="transparent"
                    dark
                    v-bind="props"
                    elevation="0"
                    @click="showReplyInput = !showReplyInput"
                >
                    <replySvg style="transform: scaleX(-1);"/>
                </v-btn>
                <!-- <v-menu>
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
                            @click="showReplyInput = !showReplyInput"
                            rounded="lg"
                            base-color="secondary"
                            density="compact"
                        >
                                <span class="flex gap-2 items-center">
                                    <component :is="replySvg"/>
                                    Reply
                                </span>
                        </v-list-item>


                        <v-list-item
                            rounded="lg"
                            href="#"
                            base-color="secondary"
                            density="compact"
                        >
                                <span class="flex gap-2 items-center">
                                    <component :is="Warning"/>
                                    Report
                                </span>
                        </v-list-item>
                    </v-list>
                </v-menu> -->
            </div>
        </div>
        <p class="fs-16 fm-book fw-400 whitespace-pre-wrap text-break mt-[0px] md:mt-[14px] leading-[18px] md:leading-[24px]" v-text="comment.content"></p>

        <div v-show="showReplyInput" class="flex items-end replies-h">
            <v-textarea label="Reply Comment" row-height="48" rows="1"
                        auto-grow
                        shaped
                        color="white"
                        class="comment-textarea fm-book"
                        v-model="content"
                        @keydown.enter="handleEnter"
                        @focus="isFocused = true"
                        @blur="isFocused = false"
            >
                <template v-slot:label>
                    <span v-show="!isFocused">Reply Comment</span>
                </template>
            </v-textarea>
            <v-btn @click="postComment" :icon="Send" color="transparent" style="box-shadow: none"/>
        </div>
        <button v-show="comment?.replies?.length > 0" @click="showReplies = !showReplies"
                class="text-[13px] md:text-[16px] font-medium text-[#69C9D0] fm-book leading-[18px] mt-[8px] lg:mt-[12px]">
            {{ showReplies ? 'Hide' : 'Replies' }}
        </button>


        <!--        reply of comment-->
        <div class="pl-6 space-y-4 relative" v-if="showReplies">
            <commentsArrow v-if="comment.parent_id === null" class="absolute -left-0 top-3 size-[13px] md:size-[22px]" />
            <SingleComment v-for="(reply) in comment.replies" :comment="reply" :manager :isReply="true"/>
        </div>
    </div>
</template>

<style scoped>
.heading {
    font-size: 19px;
    font-weight: 700
}
.label-hidden .v-label {
    display: none !important;
  }
@media (max-width: 768px) {
    .heading{
        font-size: 12px;
    }
}
</style>
