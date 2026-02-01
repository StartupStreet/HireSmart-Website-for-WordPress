<?php
/**
 * Employers & Agencies Template
 * 
 * Display all employers and recruitment agencies
 */

if (!defined('ABSPATH')) {
    exit;
}

$jobs_manager = new HireSmart_Jobs();
$employers_agencies = $jobs_manager->get_employers_agencies();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="hiresmart-directory-container">
    <div class="directory-header">
        <div>
            <h1><i class="fas fa-building"></i> Employers & Agencies</h1>
            <p class="subtitle">Connect with top companies and recruitment agencies</p>
        </div>
    </div>
    
    <div class="directory-filters">
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterDirectory('all')">
                <i class="fas fa-list"></i> All (<?php echo count($employers_agencies); ?>)
            </button>
            <button class="filter-tab" onclick="filterDirectory('employer')">
                <i class="fas fa-briefcase"></i> Employers
            </button>
            <button class="filter-tab" onclick="filterDirectory('agency')">
                <i class="fas fa-handshake"></i> Agencies
            </button>
        </div>
        
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="directory-search" placeholder="Search by company name...">
        </div>
    </div>
    
    <div class="directory-grid">
        <?php if (!empty($employers_agencies)): ?>
            <?php foreach ($employers_agencies as $entity): ?>
                <div class="entity-card" data-type="<?php echo $entity->account_type; ?>">
                    <div class="entity-header">
                        <div class="entity-avatar">
                            <?php 
                            $initials = '';
                            $name_parts = explode(' ', $entity->display_name);
                            foreach ($name_parts as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            echo esc_html(substr($initials, 0, 2));
                            ?>
                        </div>
                        
                        <div class="entity-badge <?php echo $entity->account_type; ?>">
                            <?php if ($entity->account_type === 'employer'): ?>
                                <i class="fas fa-briefcase"></i> Employer
                            <?php else: ?>
                                <i class="fas fa-handshake"></i> Agency
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="entity-info">
                        <h3><?php echo esc_html($entity->display_name); ?></h3>
                        
                        <div class="entity-meta">
                            <?php if ($entity->active_jobs > 0): ?>
                                <div class="meta-item highlight">
                                    <i class="fas fa-briefcase"></i>
                                    <strong><?php echo $entity->active_jobs; ?></strong> Active Jobs
                                </div>
                            <?php else: ?>
                                <div class="meta-item">
                                    <i class="fas fa-briefcase"></i>
                                    No active jobs
                                </div>
                            <?php endif; ?>
                            
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                Member since <?php echo date('M Y', strtotime($entity->user_registered)); ?>
                            </div>
                        </div>
                        
                        <?php 
                        $subscription_labels = [
                            'free' => 'Free',
                            'basic' => 'Basic',
                            'startup' => 'Startup',
                            'enterprise' => 'Enterprise'
                        ];
                        $tier = isset($subscription_labels[$entity->subscription_tier]) ? $subscription_labels[$entity->subscription_tier] : 'Basic';
                        ?>
                        <div class="subscription-tier">
                            <i class="fas fa-star"></i> <?php echo $tier; ?> Plan
                        </div>
                        
                        <div class="entity-actions">
                            <?php if ($entity->active_jobs > 0): ?>
                                <a href="<?php echo site_url('/jobs'); ?>" class="btn-view-jobs">
                                    <i class="fas fa-eye"></i> View Jobs
                                </a>
                            <?php endif; ?>
                            
                            <button class="btn-contact" onclick="contactEntity('<?php echo esc_js($entity->user_email); ?>', '<?php echo esc_js($entity->display_name); ?>')">
                                <i class="fas fa-envelope"></i> Contact
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-entities">
                <i class="fas fa-building"></i>
                <h3>No Employers or Agencies Yet</h3>
                <p>Be the first to join our platform!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.hiresmart-directory-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.directory-header {
    margin-bottom: 40px;
}

.directory-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 10px;
}

.directory-header .subtitle {
    font-size: 16px;
    color: #6b7280;
}

.directory-filters {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.filter-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 10px 20px;
    background: #f3f4f6;
    border: 2px solid transparent;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    color: #6b7280;
}

.filter-tab:hover,
.filter-tab.active {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.search-box input {
    width: 100%;
    padding: 12px 12px 12px 45px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 16px;
}

.directory-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.entity-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.entity-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.entity-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 16px;
}

.entity-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
}

.entity-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.entity-badge.employer {
    background: #dbeafe;
    color: #1e40af;
}

.entity-badge.agency {
    background: #fef3c7;
    color: #92400e;
}

.entity-info h3 {
    font-size: 20px;
    color: #1f2937;
    margin-bottom: 12px;
}

.entity-meta {
    margin-bottom: 12px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    color: #6b7280;
    font-size: 14px;
}

.meta-item.highlight {
    color: #2563eb;
    font-weight: 600;
}

.subscription-tier {
    padding: 8px 12px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}

.entity-actions {
    display: flex;
    gap: 8px;
}

.btn-view-jobs,
.btn-contact {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}

.btn-view-jobs {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-view-jobs:hover {
    background: #2563eb;
    color: white;
}

.btn-contact {
    background: #2563eb;
    color: white;
}

.btn-contact:hover {
    background: #1e40af;
}

.no-entities {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.no-entities i {
    font-size: 64px;
    color: #e5e7eb;
    margin-bottom: 20px;
}

.no-entities h3 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .directory-grid {
        grid-template-columns: 1fr;
    }
    
    .entity-actions {
        flex-direction: column;
    }
}
</style>

<script>
function filterDirectory(type) {
    const cards = document.querySelectorAll('.entity-card');
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update active tab
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter cards
    cards.forEach(card => {
        if (type === 'all' || card.dataset.type === type) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function contactEntity(email, name) {
    if (confirm('Contact ' + name + '?\n\nThis will open your email client.')) {
        window.location.href = 'mailto:' + email + '?subject=Inquiry from HireSmart';
    }
}

// Search functionality
document.getElementById('directory-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.entity-card');
    
    cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        if (name.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>
