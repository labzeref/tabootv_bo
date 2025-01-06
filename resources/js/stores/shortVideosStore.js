import {reactive} from "vue";

export default reactive({
    displayVideoIndex: 0,
    nextPageUrl: null,
    isLastPage: false,
    processing: false,
    list: [],
    fetchVideos() {
        if (this.isLastPage || this.processing) {
            return;
        }

        this.processing = true;

        axios.get(this.nextPageUrl ?? route('shorts.list')).then((response) => {
            this.processing = false;
            this.appendVideos(response.data.videos.data)
            this.nextPageUrl = response.data.videos.next_page_url;
            if (!response.data.videos.next_page_url) {
                this.isLastPage = true;
            }
        }).catch((error) => {
            console.error('Error fetching series data:', error);
        });
    },
    appendVideo(video) {
        this.list.unshift(video);
    },
    appendVideos(videos) {
        this.list.push(...videos);
    },
    updateDisplayVideoIndex(index) {
        this.displayVideoIndex = index;

        if (this.displayVideoIndex >= this.list.length - 1) {
            this.fetchVideos();
        }
    }
})
