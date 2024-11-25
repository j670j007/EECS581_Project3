document.addEventListener('DOMContentLoaded', fetchUserLists);

function fetchUserLists() {
    const userId = getUserIdFromURL();
    fetch(`fetch_user_lists.php?userId=${userId}`)
        .then(response => {
            console.log('Response received:', response);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            const userLists = document.getElementById('userLists');
            userLists.innerHTML = ''; // Clear previous lists
            if (data.length === 0) {
                userLists.innerHTML = '<option>No lists found.</option>';
                return;
            }
            data.forEach(list => {
                const option = document.createElement('option');
                option.textContent = list.list_name;
                option.value = list.list_id;
                userLists.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching user lists:', error);
            alert('Error loading user lists. Please try again later.');
        });
}

function getUserIdFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('userId');
}
