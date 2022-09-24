<script>
    function Del(name) {
        return confirm("Do you want to delete: " + name + " ?");
    }

    function Confirm() {
        return confirm("Do you want to login with facebook");
    }

    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
    
</script>