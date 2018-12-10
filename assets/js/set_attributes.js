class SetAttributes {
    constructor() {
        this.locations;
        this.$locations;
        this.festivalStart;
        this.$festivalStart;
        this.festivalEnd;
        this.$festivalEnd;

        this.allLockers;
        this.$allLockers;
        this.lockers;

        this.setValues();
    }

    setValues() {
        this.$locations = document.querySelector('[name="_festival_locations"]');
        this.locations = this.$locations.value.trim().split(',');
        
        this.$festivalStart = document.querySelector('[name="_festival_start"]');
        this.festivalStart = this.$festivalStart.value;
        
        this.$festivalEnd = document.querySelector('[name="_festival_end"]');
        this.festivalEnd = this.$festivalEnd.value;
        
        this.$allLockers = document.querySelectorAll('[name^="_lockers"]');
        this.allLockers = Array.from(this.$allLockers);
        
        this.lockers = this.allLockers.filter((x) => x.checked);
        
        this.lockers = this.lockers.map((x) => x.dataset.lockertype);
    }

    ajaxCall() {
        console.log("POSTID", localizedVars.postID);
        jQuery.ajax({
            url: ajaxurl,
            data: {
                'action': 'fe_set_product_atts',
                // 'data': JSON.stringify()
                'data': {ID: localizedVars.postID, Locations: this.locations, FestivalStart: this.festivalStart, FestivalEnd: this.festivalEnd, Lockers: this.lockers}
            },
            success: function(data) {
                console.log(data);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
}

let set_attributes = new SetAttributes();