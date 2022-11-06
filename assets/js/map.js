document.addEventListener('alpine:init', () => {

    import oljf from '../../../../dist/assets/js/oljf';

    Alpine.data('map', () => ({
            latitude: false,
            longitude: false,
            isLoading: false,
            initMap() {
                console.log(`map for ${this.latitude}`);
                const mapdiv = document.getElementById('openmap_offre')
                oljf()
            }
        })
    )
})