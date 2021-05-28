<template>
	<div>
		<images-uploader
			@updateImagesList="updateImagesList()"
		></images-uploader>
		<images-list
			:images="this.images"
			@updateImagesList="updateImagesList()"
		></images-list>
	</div>
</template>

<script>
	import ImagesUploader from "./uploader.vue";
	import ImagesList from "./list.vue";
	export default {
		components: { ImagesUploader, ImagesList },
		data: function () {
			return {
				images: {},
			};
		},
		methods: {
			updateImagesList() {
				axios
					.get("/api/images")
					.then((res) => {
						this.images = res.data;
					})
					.catch((err) => {
						this.images = {};
					});
			},
		},
		mounted() {
			this.images = this.updateImagesList();
		},
	};
</script>
