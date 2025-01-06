<script setup>
import SmallCross from "@/Components/svg/icons/smallCross.vue";
import VideoPlayerBtn from "@/Components/svg/icons/videoPlayerBtn.vue";
import { Link } from '@inertiajs/vue3';
import {formatAsteriskString} from "@/utils.js";

const emit = defineEmits(['destroy']);

const props = defineProps({
    notification: Object
})

const destroy = () => {
  axios.delete(route('notifications.destroy', props.notification.id))
    .then(response => {
        emit('destroy', props.notification.id);
    })
    .catch(error => {
      console.error('Error marking notification as read:', error);
    });
};
</script>

<template>
    <v-slide-y-transition>
        <div
             class="w-full flex items-start md:items-center gap-3 p-4 transition-all duration-300 rounded-lg"
             :class="notification.has_read ? 'container' : 'bg-[#393535A1] hover:bg-white/10'">
            <div v-if="notification.image" class="relative overflow-hidden min-w-fit">
                <img :src="notification.image" alt="video Image"
                     class="w-[131px] aspect-video rounded-[12px]">
                <div class="layer absolute inset-0 rounded-[12px]">
                    <video-player-btn class="md:hidden" />
                </div>
            </div>
            <div class="w-full space-y-1 md:space-y-3">

                <Link :href="notification.route">
                    <p class="text-sm font-bold leading-[24px] text-[#9F9F9F]" v-html="formatAsteriskString(notification.message, 'text-primary')"></p>
                </Link>
                <v-btn v-if="notification.btn" color="primaryDark" class="watchNow" style="box-shadow: none; font-weight: 500"
                       height="36"
                       width="117px" rounded="pill">Watch now
                </v-btn>
                <p class="md:hidden text-secondary">{{ notification.created_at }}</p>

            </div>
            <v-btn color="transparent" style="box-shadow: none" class="text-secondary crossBtn timeText" :append-icon="SmallCross"
                   @click="destroy">{{ notification.created_at }}</v-btn>

            <v-btn color="transparent" style="box-shadow: none" class="text-secondary onlyCrossBtn" width="20px" height="20px" :icon="SmallCross"
                   @click="destroy" />
        </div>
    </v-slide-y-transition>

</template>

<style scoped>
.timeText{
    color: #9F9F9F !important;
    font-size: 14px !important;
    font-weight: 400 !important;
    line-height: 24px !important;
}

.onlyCrossBtn{
    display: none;
}
@media (max-width: 768px) {

    .container{
        border-bottom: 1px solid #FFFFFF1A;
    }
    .watchNow{
        display: none;
    }
    .crossBtn{
        display: none;
    }
    .onlyCrossBtn{
        display: inline;
    }

}
.layer {
    background: linear-gradient(180deg, rgba(10, 7, 11, 0) 0%, rgba(10, 7, 11, 0.7) 100%);
}
</style>
