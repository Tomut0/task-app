<script setup>
import { Link } from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    color: {
        type: String,
        default: '#0D99FF'
    },
    size: {
        type: String,
        default: 'lg'
    }
});

const size = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'w-8 h-8';
        case 'lg':
            return 'w-16 h-16'
        default:
            return 'w-8 h-8'
    }
});
</script>

<template>
    <Link :href="'/'">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :fill="color" :class='size'>
            <path d="M5.566 4.657A4.505 4.505 0 0 1 6.75 4.5h10.5c.41 0 .806.055 1.183.157A3 3 0 0 0 15.75 3h-7.5a3 3 0 0 0-2.684 1.657ZM2.25 12a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3v-6ZM5.25 7.5c-.41 0-.806.055-1.184.157A3 3 0 0 1 6.75 6h10.5a3 3 0 0 1 2.683 1.657A4.505 4.505 0 0 0 18.75 7.5H5.25Z" />
        </svg>

    </Link>
</template>
