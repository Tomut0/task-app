<script setup>

import {CalendarDaysIcon} from "@heroicons/vue/24/outline/index.js";
import TimesheetHeader from "@/Components/TimesheetHeader.vue";
import TimesheetControls from "@/Components/TimesheetControls.vue";
import TimesheetEntry from "@/Components/TimesheetEntry.vue";
import {ref} from "vue";
import {formatDateRange} from "../date.js";

const props = defineProps({
    tasks: {
        type: Array,
        required: true
    },
    dateRange: {
        type: Array
    }
});

const createDateArray = () => {
    const today = new Date();
    const nextWeek = new Date(today);
    nextWeek.setDate(today.getDate() + 7);

    return [today, nextWeek];
};

const datePlaceholder = ref(new Date());
const dateRange = ref(props.dateRange ?? createDateArray());
</script>

<template>
    <TimesheetHeader class="flex justify-between items-center p-4">
        <span class="capitalize text-lg">
            <CalendarDaysIcon class="w-6 h-6 inline-flex" />
            {{ formatDateRange(dateRange) }}
        </span>

        <TimesheetControls :date-placeholder="datePlaceholder" />
    </TimesheetHeader>

    <hr>

    <main class="p-4 space-y-4">
        <TimesheetEntry v-for="task in tasks" :key="task.id" :task="task" class="group hover:bg-gray-200"/>
    </main>
</template>

<style scoped>

</style>
