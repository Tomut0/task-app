<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Timesheet from "@/Components/Timesheet.vue";
import {usePage} from "@inertiajs/vue3";

const { tasks, dateRange } = usePage().props;
</script>

<template>
    <AppLayout title="Задачи">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <Timesheet :tasks="tasks" :date-range="dateRange" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
