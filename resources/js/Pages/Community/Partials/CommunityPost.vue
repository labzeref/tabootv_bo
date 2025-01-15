<script setup>
import CommentArea from "./CommentArea.vue";
import { ref } from 'vue';
import Reactions from "@/Pages/Community/Partials/Recations.vue";

const showReplySection = ref(false);

const props = defineProps({
            post: 'Object',
            });
const toggleReply = () => {
    showReplySection.value = !showReplySection.value
}
</script>

<template>
    <div class="d-flex items-center gap-2 mb-3 pl-7">
        <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 2.20046C11.6607 -2.07653 22.0635 5.40774 7.5 15.0314C-7.06352 5.40867 3.33926 -2.07653 7.5 2.20046Z" fill="#9F9F9F"/>
        </svg>
        <p class="fs-14 text-secondary-9f">{{ post.recent_like }}</p>
    </div>
    <div class="comment-div">
        <v-img cover :src="post.user.small_dp" class="profile-comment-img"></v-img>
        <div class="w-full">
            <div class="d-flex items-center justify-between w-full gap-1">
                <div class="d-flex items-center gap-1 mb-1">
                    <p class="fs-15-community">{{ post.user.display_name }}</p>
                    <p class="fs-15-community font-400">·</p>
                    <p class="fs-15-community font-400">{{ post.created_at }}</p>
                </div>
                <span class="cursor-pointer">
                        <svg width="16" height="4" viewBox="0 0 16 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.68081 2.86529C8.15875 2.86529 8.5462 2.47784 8.5462 1.99991C8.5462 1.52197 8.15875 1.13452 7.68081 1.13452C7.20288 1.13452 6.81543 1.52197 6.81543 1.99991C6.81543 2.47784 7.20288 2.86529 7.68081 2.86529Z" fill="#9F9F9F" stroke="#9F9F9F" stroke-width="1.15385" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.7384 2.86529C14.2164 2.86529 14.6038 2.47784 14.6038 1.99991C14.6038 1.52197 14.2164 1.13452 13.7384 1.13452C13.2605 1.13452 12.873 1.52197 12.873 1.99991C12.873 2.47784 13.2605 2.86529 13.7384 2.86529Z" fill="#9F9F9F" stroke="#9F9F9F" stroke-width="1.15385" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M1.6232 2.86529C2.10114 2.86529 2.48858 2.47784 2.48858 1.99991C2.48858 1.52197 2.10114 1.13452 1.6232 1.13452C1.14526 1.13452 0.757812 1.52197 0.757812 1.99991C0.757812 2.47784 1.14526 2.86529 1.6232 2.86529Z" fill="#9F9F9F" stroke="#9F9F9F" stroke-width="1.15385" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                   </span>
            </div>
            <p class="fs-15-community font-400 color-white mt-1"> {{ post.caption }}</p>
            <img :src="post.post_image" v-if="post.post_image" class="object-contain w-auto rounded-[16px] mt-4 h-[350px]"/>
            <div class="d-flex items-center justify-between gap-2 mt-4">
                <Reactions :toggleReply="toggleReply" :post="post" />
                <span class="cursor-pointer" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                            <path d="M18.5595 10.1925C18.8037 9.98321 18.9258 9.87857 18.9705 9.75403C19.0098 9.64473 19.0098 9.52518 18.9705 9.41588C18.9258 9.29135 18.8037 9.1867 18.5595 8.9774L10.0866 1.71495C9.66631 1.35466 9.45614 1.17452 9.27821 1.17011C9.12357 1.16627 8.97586 1.23421 8.87813 1.35412C8.76568 1.49209 8.76568 1.76889 8.76568 2.32251V6.61884C6.63047 6.99239 4.67625 8.07433 3.224 9.69885C1.64072 11.4699 0.764907 13.7619 0.763672 16.1376V16.7497C1.81328 15.4853 3.12377 14.4627 4.60539 13.7519C5.91166 13.1253 7.32373 12.7541 8.76568 12.6563V16.8474C8.76568 17.401 8.76568 17.6778 8.87813 17.8158C8.97586 17.9357 9.12357 18.0036 9.27821 17.9998C9.45614 17.9954 9.66631 17.8152 10.0866 17.455L18.5595 10.1925Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
            </div>

        </div>
    </div>


         <v-scroll-y-transition v-show="showReplySection">

            <div class="mt-5 bg-comments">
                <CommentArea
                    :post="post"
                    :showReplySection
                />
            </div>

         </v-scroll-y-transition>
</template>
