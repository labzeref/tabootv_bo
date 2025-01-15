<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed, defineAsyncComponent, onMounted,onUnmounted, reactive, ref } from "vue";
import CommunitySidebar from "./Partials/CommunitySidebar.vue";
import PrimaryCard from "@/Components/PrimaryCard.vue";
import {Swiper, SwiperSlide} from "swiper/vue";
import CommunityPost from "@/Pages/Community/Partials/CommunityPost.vue";
import ShortVideoCard from "../Shorts/Partials/ShortVideoCard.vue";
import VuetifyDropdown from "@/Components/VuetifyDropdown.vue";
const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));
const SecondaryCard = defineAsyncComponent(() => import("@/Components/SeriesSecondaryCard.vue"));
const props = defineProps({
    creator_id: Number,
})
const creator = ref({})
const tabs = ref([
    { label: 'Videos', count: 0 },
    { label: 'Shorts', count: 0 },
    { label: 'Series', count: 0 },
    { label: 'Posts',  count: 0 },
]);
const sortingOptions = [
    {
        name: 'Newest',
        value: 'newest',
    },
    {
        name: 'Trending',
        value: 'trending',
    },
    {
        name: 'Oldest',
        value: 'old',
    },
];
const sortBy = ref(sortingOptions[0].value);
const handleSortByChange = (value) => {
    sortBy.value = value
    creatorVideosNextPage.value=""
    creatorShortsNextPage.value=""
    creatorSeriesNextPage.value=""
    creatorPostsNextPage.value=""
    creatorVideos.value = []
    creatorShorts.value = []
    creatorSeries.value = []
    creatorPosts.value = []
    if (activeTab.value === 0){
        getVideos();
    }else if(activeTab.value === 1){
        getShorts();
    }else if(activeTab.value === 2){
        getSeries();
    }else if(activeTab.value === 3){
        getPosts();
    }


}
onMounted(() => {
    axios.get(route('creators.show',props.creator_id))
        .then(response => {
            creator.value = response.data.creator
            tabs.value[0].count = response.data.creator.videos_count;
            tabs.value[1].count = response.data.creator.short_videos_count;
            tabs.value[2].count = response.data.creator.series_count;
            tabs.value[3].count = response.data.creator.posts_count;
            getVideos()

        })
})

const activeTab = ref(0);
const creatorVideos = ref([]);
const creatorVideosNextPage = ref("");
const creatorShorts = ref([]);
const creatorShortsNextPage = ref("");
const creatorSeries = ref([]);
const creatorSeriesNextPage = ref("");
const creatorPosts = ref([]);
const creatorPostsNextPage = ref("");
const selectTab = (index) => {
    if  (index === 0){
        if (creatorVideos.value.length === 0){
            getVideos();
        }
    }

    if  (index === 1){
        if (creatorShorts.value.length === 0){
            getShorts();
        }
    }

    if(index === 2){
        if (creatorSeries.value.length === 0){
            getSeries()
        }
    }

    if(index ===3){
        if (creatorPosts.value.length === 0){
            getPosts()
        }
    }

    activeTab.value = index;
};

const getVideos = () => {

    let url = creatorVideosNextPage.value || route('creators.videos',props.creator_id);
    url += '?' + new URLSearchParams({sort_by: sortBy.value}).toString();
    axios.get(url)
        .then(response => {
            creatorVideos.value.push(...response.data.videos.data)
            creatorVideosNextPage.value = response.data.videos.next_page_url

        })
}
const getShorts = () => {
    let url = creatorShortsNextPage.value || route('creators.shorts',props.creator_id);
    url += '?' + new URLSearchParams({sort_by: sortBy.value}).toString();
    axios.get(url)
        .then(response => {
            creatorShorts.value.push(...response.data.videos.data)
            creatorShortsNextPage.value = response.data.videos.next_page_url
        })
}
const getSeries = () => {

    let url = creatorSeriesNextPage.value || route('creators.series',props.creator_id);
    url += '?' + new URLSearchParams({sort_by: sortBy.value}).toString();

    axios.get(url)
        .then(response => {
            creatorSeries.value.push(...response.data.series.data)
            creatorSeriesNextPage.value = response.data.series.next_page_url

        })
}
const getPosts = () => {

    let url = creatorPostsNextPage.value || route('creators.posts',props.creator_id);
    url += '?' + new URLSearchParams({sort_by: sortBy.value}).toString();
    axios.get(url)
        .then(response => {
            creatorPosts.value.push(...response.data.posts.data)
            creatorPostsNextPage.value = response.data.posts.next_page_url
        })
}
const gap = ref(1)

// Function to handle screen resize
const updateSlidesPerView = () => {
  gap.value = window.innerWidth < 768 ? 12 : 25;
};

// Listen for window resize events
onMounted(() => {
  window.addEventListener('resize', updateSlidesPerView);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateSlidesPerView);
});

// Watch the screen size on initial load
updateSlidesPerView();
</script>

<template>
  <Head title="Creators" />

  <Page>
    <div class="grid-community">
      <CommunitySidebar />
      <div class="overflow-auto">
          <div class="creator-profile-card-bg">
            <div>
                <img :src="creator.banner" alt="" class="creator-profile-card-img" />
                <div class="card-content">
                    <img :src="creator.dp" alt="" class="creator-profile-main-img" />
                    <div class="w-full">
                        <div class="flex items-center justify-between gap-[10px]">
                            <h2 class="h2-2xsmall text-white-fb">{{ creator.name }}</h2>
                        </div>
                        <p class="fs-16 fw-400 text-dark-dc mt-1 md:mt-5 mb--32">
                            {{
                            creator.description
                            }}
                        </p>
                    </div>
                </div>
                <!-- -----------  -->
                <div class="creator-profile-tabs">
                    <div
                      v-for="(tab, index) in tabs"
                      :key="index"
                      :class="['tabs-dimen', { active: activeTab === index }]"
                      @click="selectTab(index)"
                    >
                      {{ tab.label }}
                      <br />
                      <span>{{ tab.count }}</span>
                    </div>
                </div>
                <!-- -----------  -->
            </div>
          </div>
          <!-- -----------  -->
          <div class="flex items-center justify-between gap-3 mt-6">
            <h3 class="lh-24 fw-700 capitalize">uploads</h3>
          </div>
          <!-- -----------  -->
          <div class="creator-tab-content mt-8">
              <VuetifyDropdown
                  :list="sortingOptions"
                  listName="name"
                  listValue="value"
                  label="Sort by"
                  placeholder="Newest"
                  @select="handleSortByChange"
              />
            <div v-if="activeTab === 0" class="video-tab-content">Video Contnet
                        <div class="grid grid-cols-2 xl:grid-cols-3 gap-2 md:gap-4 mt-4">
                            <primary-card v-for="(item, index) in creatorVideos" :item="item"/>
                        </div>

                        <div class="mt-5 flex items-center justify-center">
                            <v-btn v-if="creatorVideosNextPage" @click="getVideos" class="bg-transparent">
                                Load more
                            </v-btn>
                        </div>

            </div>


            <div v-else-if="activeTab === 1" class="short-tab-content">Short Content
                        <div class="mt-4 ">
                            <swiper
                            :slidesPerView="'auto'"
                            :space-between="gap"
                            >
                                <swiper-slide v-for="(video, key) in creatorShorts" :key
                                    class=" rounded-[6px] max-w-[169px] md:max-w-[190px] min-h-[265px] md:min-h-[305px] w-full relative overflow-hidden cursor-pointer">

                                    <Link :href="route('shorts.page', video.uuid)">
                                        <div class="relative">
                                            <img :src="video.card_thumbnail" alt="placeholder shorts image"
                                            class="w-full h-[265px] md:h-[305px]">
                                            <div class="absolute z-20 left-[10px] bottom-[6px]">
                                                <p class="font-medium text-[20px] truncated-heading-2 pr-2" style="line-height: 30px !important">{{ video.title }}</p>
                                                <!-- <p class="md:text-sm text-[12px]">{{ video.humans_publish_at }}</p> -->
                                            </div>
                                        </div>
                                    </Link>

                                    <div
                                    class="z-10 absolute -bottom-1 md:-bottom-10 -left-2 -right-2 top-[50%] md:top-[60%] bg-gradient-to-t from-dark to-dark/0"></div>
                                </swiper-slide>
                            </swiper>

                        </div>

                    <v-btn v-if="creatorShortsNextPage" @click="getShorts">
                        Load more
                    </v-btn>



            </div>
            <div v-else-if="activeTab === 2" class="series-tab-content">
                <!-- <SecondaryCard /> -->
                Series Content
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-x-2 md:gap-x-4 gap-y-3 md:gap-y-10 md:mt-4">
                        <div class=" relative" v-for="(series, key) in creatorSeries" :key="key">
                            <SecondaryCard :object="series"
                                           :heightCustom="'video-img-h'"
                            />
                        </div>
                    </div>
                <v-btn v-if="creatorSeriesNextPage" @click="getSeries">
                    Load more
                </v-btn>

            </div>
            <div v-else-if="activeTab === 3" class="posts-tab-content">
                Posts Content

                <div v-for="(post, index) in creatorPosts" :key="index" class="right-div-community mt-4">
                    <CommunityPost :post="post" :index="index" />
                </div>
                <div class="mt-5 flex items-center justify-center">
                    <v-btn v-if="creatorPostsNextPage" @click="getPosts" class="bg-transparent">
                        Load more
                    </v-btn>
                </div>
            </div>

          </div>
          <!-- -----------  -->
      </div>
    </div>
  </Page>
</template>

<style scoped>
.mySwiper {
    height: 80vh;
    aspect-ratio: 9/16;
    width: 100%;
}

@media (max-width: 990px) {
    .mySwiper {
        height: 90vh;
    }
}

@media (max-width: 500px) {
    .mySwiper {
        height: auto;
        width: 100%;
    }
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
