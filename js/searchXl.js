document.addEventListener('alpine:init', () => {
    Alpine.data('searchXl', () => ({
            searchIsOpen: false,
            isLoading: false,
            noResult: false,
            query: '',
            totalHits: 0,
            results: null,
            async launchSearch() {
                if (this.query === '') return;
                this.noResults = false;
                console.log(`search for ${this.query}`);
                let response = await fetch("https://www.marche.be/api/search.php?s=" + this.query)
                const t = response.text().then(function (data) {
                    return data
                })
                //   console.log(data)
                //   this.results = JSON.parse(data)
                //   this.totalHits = JSON.parse(data).length
                const data = (JSON.parse(await t))
                this.results = []
                this.totalHits = data.length
                return this;
            }
        })
    )
})