<script setup>
import dots from "@/assets/svg/dots.svg";
import {ref} from "vue";
import {Link} from "@inertiajs/vue3"
const props = defineProps({
    creator: Object,
})
const isFollowing = ref(props.creator.following);

const handleFollowToggle = () => {
    axios.post(route('creators.follow-toggle',props.creator.id))
        .then(response => {
          if (response.data.creator){
              isFollowing.value = response.data.creator.following
          }
        })
        .catch(error => {

        })
}
</script>

<template>
    <div class="creator-card-bg">
        <img :src="creator.banner" alt="" class="creator-card-img" />
        <div class="card-content">
            <img :src="creator.dp" alt="" class="creator-profile-img" />
            <div class="w-full">
                <div class="flex items-center justify-between gap-[10px]">
                    <Link :href="route('creators.profile',creator.id)">
                    <p class="fs-18 fw-500 text-white-fb">{{ creator.name }}</p>
                    </Link>

                    <div class="flex items-center gap-[10px]">
                        <img :src="dots" alt="">
                        <div class="mobile--hidden">
                            <button v-if="!isFollowing" @click="handleFollowToggle" class="btn btn-primary btn-sm">Follow</button>
                            <button v-if="isFollowing" @click="handleFollowToggle" class="btn btn-primary btn-sm">Following</button>
                        </div>
                    </div>
                </div>
                <p class="fs-16 fw-400 text-dark-9d mt-2 md:mt-6">
                    {{ creator.description }}
                </p>
            </div>
        </div>
        <div class="tab-devices-hidden">
            <button v-if="!isFollowing" @click="handleFollowToggle" class="btn btn-primary btn-sm w-[93%] mx-auto my-[15px]">Follow</button>
            <button v-if="isFollowing"  @click="handleFollowToggle" class="btn btn-primary btn-sm w-[93%] mx-auto my-[15px]">Following</button>
        </div>
    </div>

</template>
