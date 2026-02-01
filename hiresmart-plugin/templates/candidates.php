<?php
/**
 * Candidates Template
 * 
 * Display all job seekers for employers/agencies
 */

if (!defined('ABSPATH')) {
    exit;
}

$jobs_manager = new HireSmart_Jobs();
$candidates = $jobs_manager->get_all_candidates();
$is_logged_in = is_user_logged_in();
$show_limit = !$is_logged_in ? 5 : count($candidates);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="hiresmart-candidates-container">
    <div class="candidates-header">
        <div>
            <h1><i class="fas fa-users"></i> Browse Candidates</h1>
            <p class="subtitle">Find the perfect talent for your team</p>
        </div>
    </div>
    
    <div class="candidates-filters">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="candidate-search" placeholder="Search candidates by name or skills...">
        </div>
    </div>
    
    <div class="candidates-grid">
        <?php if (!empty($candidates)): ?>
            <?php foreach ($candidates as $index => $candidate): 
                $is_blurred = !$is_logged_in && $index >= $show_limit;
                $card_class = $is_blurred ? 'candidate-card blurred-card' : 'candidate-card';
            ?>
                <div class="<?php echo $card_class; ?>">
                    <div class="candidate-avatar">
                        <?php 
                        $initials = '';
                        $name_parts = explode(' ', $candidate->display_name);
                        foreach ($name_parts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                        echo esc_html(substr($initials, 0, 2));
                        ?>
                    </div>
                    
                    <div class="candidate-info">
                        <h3><?php echo esc_html($candidate->display_name); ?></h3>
                        <p class="candidate-email">
                            <i class="fas fa-envelope"></i>
                            <?php echo esc_html($candidate->user_email); ?>
                        </p>
                        
                        <?php if ($candidate->iq_score || $candidate->eq_score || $candidate->sq_score): ?>
                            <div class="candidate-scores">
                                <div class="scores-header">
                                    <i class="fas fa-chart-line"></i> AI-Analyzed Scores
                                </div>
                                
                                <?php if ($candidate->iq_score): ?>
                                    <div class="score-badge iq">
                                        <i class="fas fa-brain"></i>
                                        <span>IQ: <?php echo $candidate->iq_score; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($candidate->eq_score): ?>
                                    <div class="score-badge eq">
                                        <i class="fas fa-heart"></i>
                                        <span>EQ: <?php echo $candidate->eq_score; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($candidate->sq_score): ?>
                                    <div class="score-badge sq">
                                        <i class="fas fa-users"></i>
                                        <span>SQ: <?php echo $candidate->sq_score; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="score-note">
                                    <i class="fas fa-info-circle"></i>
                                    Scores based on resume analysis & profile integrations
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="profile-sync-status">
                            <div class="sync-header">
                                <i class="fas fa-link"></i> Profile Integrations
                            </div>
                        
                        <div class="profile-sync-status">
                            <div class="sync-header">
                                <i class="fas fa-link"></i> Profile Integrations
                            </div>
                            
                            <div class="sync-items">
                                <?php if ($candidate->linkedin_url): ?>
                                    <div class="sync-item connected">
                                        <i class="fab fa-linkedin"></i>
                                        <span>LinkedIn</span>
                                        <span class="sync-badge">✓ Connected</span>
                                    </div>
                                <?php else: ?>
                                    <div class="sync-item not-connected">
                                        <i class="fab fa-linkedin"></i>
                                        <span>LinkedIn</span>
                                        <span class="sync-badge">Not Connected</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($candidate->github_url): ?>
                                    <div class="sync-item connected">
                                        <i class="fab fa-github"></i>
                                        <span>GitHub</span>
                                        <span class="sync-badge">✓ Connected</span>
                                    </div>
                                <?php else: ?>
                                    <div class="sync-item not-connected">
                                        <i class="fab fa-github"></i>
                                        <span>GitHub</span>
                                        <span class="sync-badge">Not Connected</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($candidate->behance_url): ?>
                                    <div class="sync-item connected">
                                        <i class="fab fa-behance"></i>
                                        <span>Behance</span>
                                        <span class="sync-badge">✓ Connected</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($candidate->portfolio_url): ?>
                                    <div class="sync-item connected">
                                        <i class="fas fa-briefcase"></i>
                                        <span>Portfolio</span>
                                        <span class="sync-badge">✓ Connected</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php 
                            $connected_count = 0;
                            $connected_count += $candidate->linkedin_url ? 1 : 0;
                            $connected_count += $candidate->github_url ? 1 : 0;
                            $connected_count += $candidate->behance_url ? 1 : 0;
                            $connected_count += $candidate->portfolio_url ? 1 : 0;
                            $sync_percentage = ($connected_count / 4) * 100;
                            ?>
                            <div class="sync-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $sync_percentage; ?>%"></div>
                                </div>
                                <span class="progress-text"><?php echo round($sync_percentage); ?>% Profile Sync Complete</span>
                            </div>
                        </div>
                        
                        <div class="candidate-links">
                            <?php if ($candidate->linkedin_url): ?>
                                <a href="<?php echo esc_url($candidate->linkedin_url); ?>" target="_blank" class="profile-link linkedin">
                                    <i class="fab fa-linkedin"></i> LinkedIn
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($candidate->github_url): ?>
                                <a href="<?php echo esc_url($candidate->github_url); ?>" target="_blank" class="profile-link github">
                                    <i class="fab fa-github"></i> GitHub
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($candidate->portfolio_url): ?>
                                <a href="<?php echo esc_url($candidate->portfolio_url); ?>" target="_blank" class="profile-link portfolio">
                                    <i class="fas fa-briefcase"></i> Portfolio
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="candidate-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                Joined <?php echo human_time_diff(strtotime($candidate->user_registered), current_time('timestamp')); ?> ago
                            </span>
                        </div>
                        
                        <button class="btn-contact" onclick="contactCandidate('<?php echo esc_js($candidate->user_email); ?>', '<?php echo esc_js($candidate->display_name); ?>')">
                            <i class="fas fa-envelope"></i> Contact Candidate
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (!$is_logged_in && count($candidates) > 5): ?>
                <div class="access-gate">
                    <div class="gate-content">
                        <i class="fas fa-lock"></i>
                        <h3>Want to see more candidates?</h3>
                        <p>Login or subscribe to view all <?php echo count($candidates); ?> talented job seekers</p>
                        <div class="gate-actions">
                            <a href="<?php echo wp_login_url(); ?>" class="btn-login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="<?php echo site_url('/register'); ?>" class="btn-signup">
                                <i class="fas fa-user-plus"></i> Sign Up Free
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-candidates">
                <i class="fas fa-users"></i>
                <h3>No Candidates Yet</h3>
                <p>Check back soon as more job seekers join the platform!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.hiresmart-candidates-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.candidates-header {
    margin-bottom: 40px;
}

.candidates-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 10px;
}

.candidates-header .subtitle {
    font-size: 16px;
    color: #6b7280;
}

.candidates-filters {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
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

.candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.candidate-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
}

.candidate-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.candidate-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: bold;
    margin: 0 auto 20px;
}

.candidate-info h3 {
    font-size: 20px;
    color: #1f2937;
    margin-bottom: 8px;
}

.candidate-email {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 16px;
}

.candidate-scores {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
}

.scores-header {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.candidate-scores .score-badge {
    justify-content: flex-start;
}

.score-note {
    font-size: 11px;
    color: #6b7280;
    font-style: italic;
    margin-top: 4px;
}

.profile-sync-status {
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
    margin-bottom: 16px;
}

.sync-header {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.sync-items {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 12px;
}

.sync-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    padding: 6px;
    border-radius: 4px;
}

.sync-item.connected {
    background: #d1fae5;
    color: #065f46;
}

.sync-item.not-connected {
    background: #fee2e2;
    color: #991b1b;
}

.sync-badge {
    margin-left: auto;
    font-size: 10px;
    font-weight: 600;
}

.sync-progress {
    margin-top: 12px;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 4px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    transition: width 0.3s;
}

.progress-text {
    font-size: 11px;
    color: #6b7280;
    font-weight: 600;
}

.blurred-card {
    filter: blur(5px);
    pointer-events: none;
    opacity: 0.5;
}

.access-gate {
    background: white;
    border-radius: 12px;
    padding: 60px 40px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
    grid-column: 1 / -1;
    margin-top: -50px;
    position: relative;
    z-index: 10;
}

.gate-content i {
    font-size: 48px;
    color: #2563eb;
    margin-bottom: 20px;
}

.gate-content h3 {
    font-size: 28px;
    color: #1f2937;
    margin-bottom: 12px;
}

.gate-content p {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 24px;
}

.gate-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.btn-login,
.btn-signup {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-login {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-login:hover {
    background: #2563eb;
    color: white;
}

.btn-signup {
    background: #2563eb;
    color: white;
}

.btn-signup:hover {
    background: #1e40af;
}

.score-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.score-badge.iq {
    background: #dbeafe;
    color: #1e40af;
}

.score-badge.eq {
    background: #fce7f3;
    color: #be185d;
}

.score-badge.sq {
    background: #d1fae5;
    color: #065f46;
}

.candidate-links {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.profile-link {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.3s;
}

.profile-link.linkedin {
    background: #0077b5;
    color: white;
}

.profile-link.github {
    background: #333;
    color: white;
}

.profile-link.portfolio {
    background: #8b5cf6;
    color: white;
}

.profile-link:hover {
    transform: scale(1.05);
}

.candidate-meta {
    padding: 12px 0;
    border-top: 1px solid #e5e7eb;
    margin-bottom: 16px;
}

.meta-item {
    font-size: 13px;
    color: #6b7280;
}

.btn-contact {
    width: 100%;
    padding: 12px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-contact:hover {
    background: #1e40af;
}

.no-candidates {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.no-candidates i {
    font-size: 64px;
    color: #e5e7eb;
    margin-bottom: 20px;
}

.no-candidates h3 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .candidates-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function contactCandidate(email, name) {
    if (confirm('Contact ' + name + '?\n\nThis will open your email client.')) {
        window.location.href = 'mailto:' + email + '?subject=Job Opportunity from HireSmart';
    }
}
</script>
