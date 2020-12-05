<template>
    <div class="py-12">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
            <ul class="md:grid md:grid-cols-3 md:gap-y-10">
                <li v-for="(lesson, index) in lessons">
                    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                        <img class="w-full" :src="lesson.cover_img" alt="Sunset in the mountains">
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">{{lesson.name}}</div>
                            <p class="text-gray-700 text-base">
                                {{lesson.brief}}
                            </p>
                        </div>
                        <div class="px-6 py-4" v-if="lesson.price == 0">
                            <button class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                免费学习
                            </button>
                        </div>
                        <div class="px-6 py-4" v-else>
                            <a :href=" '/lessons/' + lesson.id" class="bg-transparent hover:bg-blue-500 text-indigo-600 font-semibold hover:text-white py-2 px-4 border border-indigo-600 hover:border-transparent rounded">
                                立即解锁
                            </a>
                        </div>

                    </div>
                </li>

            </ul>

        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                lessons: {},
            }
        },
        created() {
            this.getCases()
        },
        methods: {
            getCases() {
                axios.get(route('api.cases')).
                then(response => {
                    {
                        console.log('success');
                        console.log(response);
                        this.lessons = response.data.data
                    }
                }).catch(function (err) {
                    console.log('error');
                    console.log(err);
                })
            }
        }
    }
</script>
