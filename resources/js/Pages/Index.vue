<template>
    <Layout>
        <div class="container mx-auto">
            <p>Test</p>
        </div>
        <div class="container flex items-center justify-center mx-auto py-2 h-full">
            <div class="middle-of-the-screen bg-white">
                <img id="stream"
                     alt="image"
                     class="w-full h-full">
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "@Layout/Layout.vue";

export default {
    components: { Layout },
};
</script>