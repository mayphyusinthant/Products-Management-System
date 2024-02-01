<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { ref , onMounted} from 'vue';

import { getCookie } from '@/utils'; // Adjust the path based on your project structure

let darkMode = null;

onMounted(() => {
  if(getCookie('darkMode') == 'on'){
    darkMode = true;
  }else{
    darkMode = false;
  }

  console.log(darkMode)
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"  :class="{ 'dark:text-white': darkMode}">
                Dashboard
            </h2>
        </template>

        <div class="py-12" >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" :class="{ 'dark:bg-gray-800': darkMode }">
                <div class=" overflow-hidden shadow-xl sm:rounded-lg" >
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
