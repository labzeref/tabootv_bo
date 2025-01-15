<script setup>
import { Head } from "@inertiajs/vue3";
import { defineAsyncComponent, onMounted, onUnmounted, ref } from "vue";
import CommunityPost from "./Partials/CommunityPost.vue";
import CreatePost from "@/Pages/Community/Partials/CreatePost.vue";
import CommunitySidebar from './Partials/CommunitySidebar.vue'
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';


const Page = defineAsyncComponent(() => import("@/Components/Page.vue"));


const props = defineProps({
    post_id: Number,
});

const pageProps = usePage().props;
const authUser = pageProps.auth.user;
const posts = ref([]);
const next_page_url = ref("");
const observer = ref(null);

const handleIntersect = (entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            console.log(`Div ${entry.target.id} is in view!`);
            loadMorePosts();
        }
    });
};

const loadMorePosts = () => {
    if (next_page_url.value) {
        axios.get(next_page_url.value)
            .then(response => {
                posts.value.push(...response.data.posts.data);
                next_page_url.value = response.data.posts.next_page_url;
            })
            .catch(err => console.error(err));
    }
};

const observeSections = () => {
    const sections = document.querySelectorAll(".section");
    sections.forEach((section) => observer.value.observe(section));
};

onMounted(() => {
    // Fetch initial posts
    axios.get(route('posts.index', props.post_id))
        .then((response) => {
            posts.value = response.data.posts.data;
            next_page_url.value = response.data.posts.next_page_url;

            // Initialize Intersection Observer
            observer.value = new IntersectionObserver(handleIntersect, {
                root: null,
                rootMargin: "0px",
                threshold: 0.1, // Trigger when 10% of the div is visible
            });

            // Observe sections after posts are rendered
            observeSections();
        })
        .catch(err => console.error(err));
});

onUnmounted(() => {
    if (observer.value) {
        observer.value.disconnect();
    }
});

const handleDeletePost = (id) => {
    posts.value = posts.value.filter(post => post.id !== id);
}
</script>

<template>
    <Head title="Community"/>

    <Page class="custom-community-scroll">
        <div class="grid-community">
            <CommunitySidebar />
            <div class="overflow-auto">
                <createPost v-if="authUser.channel" :posts="posts" />
                <!-- Render posts -->
                <div v-for="(post, index) in posts" :key="index" class="right-div-community">

                  <CommunityPost :post="post" :index="index" @delete="handleDeletePost"/>
                </div>

                <!-- Section to observe -->
                <div class="section" id="section1">
                    <span v-if="next_page_url">
                Loading...

                    </span>
                    <span v-if="!next_page_url">
                No more data

                    </span>
                </div>
            </div>
        </div>
    </Page>
</template>
<style>
.loading-indicator {
    text-align: center;
    padding: 1rem;
    color: #888;
    font-size: 1rem;
}
</style>
