try {

    scoresMngr.setLimits({
        "views": [
            { "from": 0, "to": 1, "per": 0 },
	        { "from": 1, "to": 2, "per": 5 },
            { "from": 2, "to": 3, "per": 15 },
            { "from": 3, "to": 4, "per": 20 },
	        { "from": 4, "to": 6, "per": 35 },
	        { "from": 6, "to": 12, "per": 55 },
	        { "from": 12, "to": 15, "per": 75 },
	        { "from": 15, "to": Infinity, "per": 90 }
        ],

        "injections": [
            { "from": 0, "to": 3, "per": 0 },
	        { "from": 3, "to": 8, "per": 10 },
	        { "from": 8, "to": 12, "per": 15 },
	        { "from": 12, "to": 18, "per": 30 },
	        { "from": 18, "to": 50, "per": 70 },
	        { "from": 50, "to": Infinity, "per": 95 }
        ]

    });
    
    
    


}

catch (e) {


}

