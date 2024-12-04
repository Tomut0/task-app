<script setup>
import {ClockIcon} from "@heroicons/vue/24/outline/index.js";
import {TrashIcon, PencilIcon, CheckIcon, UserPlusIcon, UserMinusIcon} from "@heroicons/vue/24/solid/index.js";
import {formatToDDMM} from "../date.js";
import {router, usePage} from "@inertiajs/vue3";
import {computed, reactive, ref} from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";

const props = defineProps(['task']);

const isEditing = ref(false);
const date = ref(null);

const taskWrapper = reactive({
    title: props.task.title ?? "Новая задача",
    description: props.task.description ?? "Описание",
    created_at: props.task.created_at ?? new Date(),
    expired_at: props.task.expired_at ?? null
});

const taskCreatedAt = computed(() => {
    if (taskWrapper.expired_at) {
        if (Array.isArray(taskWrapper.expired_at)) {
            return taskWrapper.expired_at[0];
        }

        return taskWrapper.expired_at;
    }
});

const taskExpiredAt = computed(() => {
    if (Array.isArray(taskWrapper.expired_at)) {
        return taskWrapper.expired_at[1];
    }

    return null;
});


async function deleteTask() {
    router.visit(route('timesheet.delete', {task: props.task.id}), {
        method: "delete",
    });
}

function editTask() {
    isEditing.value = true;
}

async function saveTask() {
    isEditing.value = false;

    await axios.put(route('timesheet.update', {task: props.task.id}), {
        title: taskWrapper.title,
        description: taskWrapper.description,
        created_at: taskCreatedAt.value,
        expired_at: taskExpiredAt.value
    });
}

const taskDate = computed(() => {
    if (!taskWrapper.expired_at) {
        return formatToDDMM(taskWrapper.created_at * 1000);
    }

    if (Array.isArray(taskWrapper.expired_at)) {
        return `${formatToDDMM(taskWrapper.created_at * 1000)} - ${formatToDDMM(taskWrapper.expired_at[1])}`
    } else {
        return `${formatToDDMM(taskWrapper.created_at * 1000)} - ${formatToDDMM(taskWrapper.expired_at * 1000)}`
    }
});

function handleDate(v) {
    taskWrapper.expired_at = v;
    saveTask();
}

function joinTask() {
    router.visit(route('timesheet.join', {task: props.task.id}));
}

function leaveTask() {
    router.visit(route('timesheet.leave', {task: props.task.id}));
}

const isUserInTask = computed(() => props.task.users.some(user => user.id === usePage().props.auth.user.id));
const userNames = computed(() => {
    const uniqueNames = [...new Set(props.task.users.map(user => user.name))];
    return uniqueNames.join(', ');
});
</script>

<template>
    <div class="p-4 border-2 border-dashed grid grid-cols-2 items-center">
        <div class="outline-none border-0">
            <span v-if="userNames">Сейчас выполняют: {{ userNames }}</span>
            <input v-model="taskWrapper.title" :placeholder="task.title" :disabled="!isEditing"
                   class="font-semibold task__input focus:ring-0 group-hover:bg-gray-200"
                   :class="{ active: isEditing }">
            <textarea v-model="taskWrapper.description" rows="2" cols="50" maxlength="125"
                      :placeholder="task.description" :disabled="!isEditing"
                      class="text-gray-400 task__input focus:ring-0 group-hover:bg-gray-200"
                      :class="{ active: isEditing }"/>
        </div>

        <div class="flex justify-self-end justify-center items-center space-x-2 h-full">
            <div class="flex justify-center">
                <ClockIcon class="w-6 h-6 mr-1" v-if="!isEditing"/>
                <span class="capitalize" v-if="!isEditing">{{ taskDate }}</span>

                <VueDatePicker v-else v-model="date" @update:model-value="(v) => handleDate(v)"
                               placeholder="Установить время выполнения"
                               :timezone="usePage().props.timezone"
                               :enable-time-picker="false"
                               :locale="usePage().props.locale" range/>
            </div>

            <span v-if="isEditing && !isUserInTask" title="Присоединиться к выполнению задания">
                <UserPlusIcon class="w-6 h-6 transition easy-in duration-200 hover:text-space-600 cursor-pointer"
                            @click="joinTask"/>
            </span>

            <span v-if="isEditing && isUserInTask" title="Выйти из задания">
                <UserMinusIcon class="w-6 h-6 transition easy-in duration-200 hover:text-space-600 cursor-pointer"
                              @click="leaveTask"/>
            </span>

            <div v-if="isEditing" class="h-1/2 w-px bg-gray-500"></div>

            <span v-if="!isEditing" title="Редактировать задание">
                <PencilIcon class="w-6 h-6 transition easy-in duration-200 hover:text-space-600 cursor-pointer"
                            @click="editTask"/>
            </span>
            <span v-else title="Сохранить задание">
                <CheckIcon class="w-6 h-6 transition easy-in duration-200 hover:text-space-600 cursor-pointer"
                           @click="saveTask"/>
            </span>
            <span title="Удалить задание">
                <TrashIcon class="w-6 h-6 transition easy-in duration-200 hover:text-red-600 cursor-pointer"
                       @click="deleteTask"/>
            </span>
        </div>
    </div>
</template>

<style scoped>
.task__input {
    border: 0;
    outline: none;
    padding: 0;
    width: 100%;
}

.active {
    border-bottom: 1px solid #ccc;
}

:deep(textarea) {
    resize: none;
    overflow: auto;
    max-height: 300px;
}
</style>
