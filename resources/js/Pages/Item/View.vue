<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
    import Welcome from '@/Components/Welcome.vue';
    import { Link, Head } from '@inertiajs/vue3';
    import { defineProps , watch } from 'vue';
    import { reactive, ref, onMounted, watchEffect} from 'vue';

   
    const { item , referrer} = defineProps(['item', 'referrer']);

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

<!-- resources/js/Pages/Items/Index.vue -->
<template #header>
    <AppLayout title="Item" :class="{ 'dark-mode-class': darkMode }">
        <template #header >



            <div class="grid grid-cols-2" >
                <h2 class="font-semibold custom-text-xl  leading-tight">
                    <Link :href="route('items.index')" class = "text-pink-900"  :class="{ 'dark:text-gray-800': darkMode }">Items</Link>
                    <span class="text-gray-600"> / View Detail </span>
                </h2>
            
          
          </div>
          
        </template>
        <div class="py-12 text-center">
            <div class="max-w-screen-md mx-auto bg-pink-950 p-5 sm:px-6 lg:px text-white overflow-hidden shadow-xl sm:rounded-lg" :class="{ 'dark:bg-gray-800': darkMode }">
                <div class="col-span-4 mx-auto" :class="{ 'dark:text-white': darkMode}" >
                    <b>{{ item.name }}</b><br/>
                    <img :src="`http://127.0.0.1:8000/${item.img}`" class="block mx-auto h-auto object-cover mb-4 w-100" alt="Item Image">
                    <p class="text-justify">{{ item.description }}</p> <br/>
                    <hr/>
                    <b>{{ item.artType }}</b>
                    <p>{{ item.size }} - {{ item.medium }}</p>
                    <p>{{ item.price }} MMK</p>
                    <button  class=" ml-1  text-white py-2 px-4 rounded hover:bg-gray-500  bg-pink-900 focus:shadow-outline focus:outline-none">

                    <Link v-if="referrer && referrer.includes('admin/items')" :href="route('admin.index')" class="text-white" :class="{ 'dark:text-white': darkMode }">Back</Link>
                    <Link v-else-if="referrer && referrer.includes('items/explore')" :href="route('items.explore')" class="text-white" :class="{ 'dark:text-white': darkMode }">Back</Link>
                    <Link v-else-if="referrer && referrer.includes('items')" :href="route('items.index')" class="text-white" :class="{ 'dark:text-white': darkMode }">Back</Link>

                    </button>
                </div>
            </div>
        </div>
        
        
        
    </AppLayout>
</template>


