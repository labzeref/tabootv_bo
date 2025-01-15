<script setup>
import { ref } from "vue";
import axios from "axios";
import placeholderImg from '@/assets/choose-placeholder.svg'

const props = defineProps({
    posts: {
        type: Array,
        required: false,
        default: () => [], // Provide a default value to avoid null issues
           }
})
const postText = ref(""); // For the post text
const postImage = ref(null); // For the uploaded image
const showImage = ref(null); // For the uploaded image
const errors = ref([]); // For validation errors

const handleFileChange = (event) => {
    postImage.value = event.target.files[0]; // Store the selected file
    const file = event.target.files[0]; // Get the selected file
    if (file) {
        showImage.value = URL.createObjectURL(file); // Create an object URL
    }
};
const handleFileEmpty = () => {
    postImage.value = null;
    showImage.value = null;
};

const submitPost = async () => {
    if (!postText.value.trim() && !postImage.value) {
        errors.value = ["Post cannot be empty. Please add text or an image."];
        return;
    }

    const formData = new FormData();
    formData.append("caption", postText.value);
    if (postImage.value) {
        formData.append("post_image", postImage.value); // Attach the image file
    }

    try {
        const response = await axios.post(route('posts.store'), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        }).then(response => {
            let newPost = response.data.post
            props.posts.unshift(newPost)
        });

        alert("Post created successfully!");
        postText.value = "";
        postImage.value = null;
        showImage.value = null;
        errors.value = [];
    } catch (error) {
        // Handle errors (e.g., validation or server errors)
        console.error("Error creating post:", error);
    }
};
</script>

<template>
    <div class="right-div-community ">

        <div class="border-post">
            <!-- Form -->
            <div class="form-group">
                <textarea
                    v-model="postText"
                    placeholder="Write something..."
                    class="textarea"
                    rows="4"
                ></textarea>
            </div>
        <div class="flex items-center justify-between">
                <div class="form-group mb-0 flex gap-3 items-center">
                    <label v-if="!showImage" for="postImage" class="file-label">
                        <input
                        id="postImage"
                        type="file"
                        class="file-input"
                        accept="image/*"
                        @change="handleFileChange"
                        style="display: none;"
                        />
                        <img :src="placeholderImg" alt="" class="cursor-pointer h-10">
                    </label>
                    <div v-if="showImage" class="relative">
                        <img :src="showImage" alt="Uploaded Image" v-if="showImage" class="w-auto h-16 object-contain rounded" />
                        <div @click="handleFileEmpty" class="absolute -right-2 -top-5 text-[#AB0013] fw-700 text-[24px] cursor-pointer">x</div>
                    </div>
                    </div>
                <button @click="submitPost" class="submit-button max-w-[115px]">Post</button>
        </div>

            <!-- Errors -->
            <div v-if="errors.length" class="error-messages">
                <p v-for="(error, index) in errors" :key="index" class="error">{{ error }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>

.textarea {
    width: 100%;
    padding: 0.5em;
    border-radius: 4px;
    border: 1px solid transparent;
    background-color: transparent;
    color: white;
    resize: none;
}
.textarea:focus {
    --tw-ring-color: transparent;
    border: 1px solid transparent;
    background-color: transparent;

}
.file-label {
    display: block;
    margin-bottom: 0.5em;
    color: #aaa;
}
.file-input {
    display: block;
    width: 100%;
    padding: 0.5em;
    background-color: #333;
    color: white;
    border-radius: 4px;
}
.submit-button {
    display: block;
    width: 100%;
    padding: 0.55em;
    border: none;
    border-radius: 24px;
    background-color: #AB0013;
    color: white;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
    font-weight: 600;
}
.submit-button:hover {
    background-color: #ab00144d;
}
.error-messages {
    margin-top: 1em;
    color: #e74c3c;
}
</style>
