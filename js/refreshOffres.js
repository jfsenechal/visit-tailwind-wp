document.addEventListener('alpine:init', () => {
    Alpine.data('refreshOffres', () => ({
            filtreSelected: null,
            language: '',
            isLoading: false,
            currentCategory: 0,
            offres: [],
            async launchRefresh(e) {
                console.log(this.currentCategory)
                this.filtreSelected = e.target.dataset.filtre
                if (this.filtreSelected === null) return;
                this.noResults = false;
                console.log(`search for ${this.query}`);
                const url = `https://visit.marche.be/${this.language}/wp-json/pivot/offres/${this.currentCategory}/${this.filtreSelected}`;
                console.log(url)
                let response = await fetch(url)
                    .then(function (response) {
                        // The API call was successful!
                        return response.json();
                    })
                    .then(function (data) {
                        // This is the JSON from our response
                        return data;
                    })
                    .catch(function (err) {
                        // There was an error
                        console.warn("Something went wrong.", err);
                        return err
                    });
                console.log(response)
            }
        })
    )
})